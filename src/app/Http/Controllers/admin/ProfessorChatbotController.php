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


class ProfessorChatbotController extends Controller
{


    public function index()
    {
        return view('professor.chatbot');
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
- Ajude com idiomas, estudos, planejamento e educação.
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
