<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContatoController extends Controller
{
    public function contato()
    {
        return view('site.contato.contato');
    }

  public function enviar(Request $request)
{
    $request->validate([
        'nome'     => 'required|string|max:255',
        'email'    => 'required|email',
        'assunto'  => 'required|string|max:255',
        'mensagem' => 'required|string',
    ], [
        'nome.required'     => 'O campo nome é obrigatório.',
        'email.required'    => 'O campo e-mail é obrigatório.',
        'email.email'       => 'Informe um e-mail válido.',
        'assunto.required'  => 'O campo assunto é obrigatório.',
        'mensagem.required' => 'O campo mensagem é obrigatório.',
    ]);

    \Mail::raw(
        "Nome: {$request->nome}\nE-mail: {$request->email}\nAssunto: {$request->assunto}\n\nMensagem:\n{$request->mensagem}",
        function ($message) use ($request) {
            $message->to('anddrem89@gmail.com')
        ->subject("Contato: {$request->assunto}");
        }
    );

    $textoWhatsapp = urlencode(
        "📩 *Nova mensagem do site*\n\n"
        . "*Nome:* {$request->nome}\n"
        . "*E-mail:* {$request->email}\n"
        . "*Assunto:* {$request->assunto}\n\n"
        . "*Mensagem:*\n{$request->mensagem}"
    );

    $whatsappUrl = "https://api.whatsapp.com/send?phone=5511999612140&text={$textoWhatsapp}";

    return redirect()->route('contato')->with('sucesso', 'Mensagem enviada com sucesso!')->with('whatsapp_url', $whatsappUrl);
}

}
