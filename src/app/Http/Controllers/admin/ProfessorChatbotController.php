<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfessorChatbotController extends Controller
{
    public function index()
    {
        return view('professor.chatbot');
    }

    public function mensagem(Request $request)
    {
        $request->validate([
            'mensagemDoUsuario' => 'required|string'
        ]);

        if (!Auth::guard('admin')->check()) {

            return response()->json([
                'erro' => 'Usuário não autenticado'
            ], 403);

        }

        try {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.groq.key'),
                'Content-Type' => 'application/json'
            ])->post(
                'https://api.groq.com/openai/v1/chat/completions',
                [
                    'model' => config('services.groq.model'),

                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Você é a Traduca AI, assistente virtual da plataforma Traduca Idiomas. Ajude professores com planos de aula, atividades, exercícios, provas e correções.'
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


            if (!$response->successful()) {

                Log::error('Erro Groq Professor: ' . $response->body());

                return response()->json([
                    'text' => 'Não foi possível conectar com a inteligência artificial.',
                    'card' => null
                ], 500);
            }


            $dados = $response->json();

            return response()->json([
                'text' => $dados['choices'][0]['message']['content']
                    ?? 'Não consegui gerar uma resposta.',
                'card' => null
            ]);


        } catch (\Exception $e) {

            Log::error('Erro IA Professor: ' . $e->getMessage());

            return response()->json([
                'text' => 'Ocorreu um erro ao processar sua mensagem.',
                'card' => null
            ], 500);
        }
    }
}