<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\ChatbotMensagem;
use App\Models\Aula;
use App\Models\Materiais;
use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Curso;
use App\Models\Nivel;
use App\Models\Presenca;


class ProfessorChatbotController extends Controller
{


    public function index()
    {
        return view('professor.chatbot');
    }



    public function dados(Request $request)
    {
        $professor = Auth::guard('admin')->user();

        return response()->json([
            'success' => true,
            'perfil' => 'professor',
            'usuario' => $professor ? [
                'id' => $professor->getAuthIdentifier(),
                'nome' => trim($professor->nome_professor ?? 'Professor'),
            ] : null,
        ]);
    }



    public function historico()
    {

        $professorId = Auth::guard('admin')->id();


        $mensagens = ChatbotMensagem::where(
            'id_usuario',
            $professorId
        )
            ->orderBy('created_at', 'asc')
            ->get();



        return response()->json($mensagens);
    }





    public function mensagem(Request $request)
    {


        $request->validate([
            'mensagemDoUsuario' => 'required|string'
        ]);



        $professorId = Auth::guard('admin')->id();



        /*
        SALVA MENSAGEM DO PROFESSOR
        */


        ChatbotMensagem::create([

            'id_usuario' => $professorId,

            'tipo' => 'user',

            'mensagem' => $request->mensagemDoUsuario

        ]);





        /*
        BUSCA DADOS DO PROFESSOR
        */


        $aulas = Aula::where(
            'id_professor',
            $professorId
        )
            ->get();



        $materiais = Materiais::where(
            'id_professor',
            $professorId
        )
            ->get();



        $contexto = "";


        $contexto .= "Aulas do professor:\n";


        foreach ($aulas as $aula) {

            $contexto .=
                "- {$aula->titulo_aulas}
            dia {$aula->data_aulas}
            horário {$aula->hora_aulas}\n";
        }



        $contexto .= "\nMateriais:\n";


        foreach ($materiais as $material) {

            $contexto .=
                "- {$material->titulo_materiais}\n";
        }



        /*
        BUSCA DADOS DOS ALUNOS DA PLATAFORMA
        */


        $alunos = Aluno::all();

        $contexto .= "\nDADOS DOS ALUNOS DA PLATAFORMA:\n";

        foreach ($alunos as $aluno) {

            $matriculas = Matricula::where('id_aluno', $aluno->id_aluno)->get();

            $cursosNomes = [];

            foreach ($matriculas as $matricula) {

                $curso = Curso::find($matricula->id_curso);
                $nivel = Nivel::find($matricula->id_nivel);

                $cursosNomes[] = trim(
                    ($curso->nome_curso ?? 'Curso') .
                    ' - ' .
                    ($nivel->nome_nivel ?? 'Nível')
                );
            }

            $presencas = Presenca::where('id_aluno', $aluno->id_aluno)->get();
            $totalPresencas = $presencas->count();
            $totalPresente = $presencas->where('status_presenca', 'PRESENTE')->count();
            $percPresenca = $totalPresencas > 0
                ? round(($totalPresente / $totalPresencas) * 100)
                : 0;

            $contexto .=
                "- Aluno: {$aluno->nome_aluno} | " .
                "Email: {$aluno->email_aluno} | " .
                "Curso: " . implode(', ', $cursosNomes) . " | " .
                "Nível: {$aluno->nivel_aluno} | " .
                "Presença: {$percPresenca}% | " .
                "Status: {$aluno->status_aluno}\n";
        }

        $contexto .= "\n";



        try {


            $response = Http::timeout(60)
                ->retry(3, 2000)
                ->withHeaders([

                    'Authorization' =>
                    'Bearer ' . config('services.groq.key'),

                    'Content-Type' => 'application/json'

                ])
                ->post(
                    'https://api.groq.com/openai/v1/chat/completions',
                    [

                        'model' => config('services.groq.model'),


                        'messages' => [


                            [

                                'role' => 'system',

                                'content' => "

Você é a Traduca AI.

Você é uma assistente geral inteligente da plataforma Traduca Idiomas.

Ajude professores e alunos.

Você possui acesso aos dados abaixo:

$contexto


Regras:

- Responda em português.
- Seja objetiva.
- Quando perguntarem sobre aulas use os dados disponíveis.
- Quando perguntarem sobre materiais use os dados disponíveis.
- Quando perguntarem sobre alunos, use os dados reais dos alunos disponíveis (nome, email, curso, nível, presença, status).
- Ajude com idiomas, estudos, planejamento e educação.
- Nunca invente dados de alunos que não estejam no contexto.
"

                            ],



                            [

                                'role' => 'user',

                                'content' => $request->mensagemDoUsuario

                            ]

                        ],


                        'temperature' => 0.7,

                        'max_tokens' => 1024

                    ]
                );





            $dados = $response->json();


            if (!$response->successful()) {

                Log::error('Erro Groq:', $dados);

                return response()->json([
                    'text' => 'Não consegui acessar a inteligência artificial.'
                ]);
            }


            $resposta =
                $dados['choices'][0]['message']['content']
                ??
                'Não consegui responder.';



            /*
            SALVA RESPOSTA DA IA
            */


            ChatbotMensagem::create([

                'id_usuario' => $professorId,

                'tipo' => 'ai',

                'mensagem' => $resposta

            ]);





            return response()->json([

                'text' => $resposta,

                'card' => null

            ]);
        } catch (\Exception $e) {

            Log::error(
                'Erro IA Professor: ' . $e->getMessage()
            );

            return response()->json([

                'text' => 'A Traduca AI está temporariamente indisponível. Tente novamente.'

            ]);
        }
    }
}