<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoPainel;
use App\Support\Upload;
use Illuminate\Http\Request;
class SiteController extends Controller
{
    public function index()
    {
        $config = [
            'banner1_titulo'     => ConfiguracaoPainel::get('banner1_titulo', 'Ingles profissional com metodo claro.'),
            'banner1_subtitulo'  => ConfiguracaoPainel::get('banner1_subtitulo', 'Treinamento para reunioes, entrevistas e apresentacoes.'),
            'banner1_eyebrow'    => ConfiguracaoPainel::get('banner1_eyebrow', 'TraducaIdiomas · English & Professional Skills'),
            'banner1_imagem'     => ConfiguracaoPainel::get('banner1_imagem', ''),
            'banner2_titulo'     => ConfiguracaoPainel::get('banner2_titulo', 'Da aula aplicacao real.'),
            'banner2_subtitulo'  => ConfiguracaoPainel::get('banner2_subtitulo', 'Metodologia pratica, material objetivo e feedback continuo.'),
            'banner2_eyebrow'    => ConfiguracaoPainel::get('banner2_eyebrow', 'Expertise · Idiomas com estrategia'),
            'banner2_imagem'     => ConfiguracaoPainel::get('banner2_imagem', ''),
            'info_titulo'        => ConfiguracaoPainel::get('info_titulo', 'Transforme suas ideias em textos de excelencia'),
            'info_subtitulo'     => ConfiguracaoPainel::get('info_subtitulo', 'Oferecemos servicos especializados de consultoria.'),
            'sobre_titulo'       => ConfiguracaoPainel::get('sobre_titulo', 'Biografia'),
            'sobre_texto'        => ConfiguracaoPainel::get('sobre_texto', 'Sou Renato Caetano, consultor e professor trilingue.'),
            'sobre_pagina_texto' => ConfiguracaoPainel::get('sobre_pagina_texto', 'Atuo no setor corporativo e academico.'),
            'sobre_foto'         => ConfiguracaoPainel::get('sobre_foto', ''),
            'cor_primaria'       => ConfiguracaoPainel::get('cor_primaria', '#0d6efd'),
            'cor_secundaria'     => ConfiguracaoPainel::get('cor_secundaria', '#198754'),
            'logo_painel'        => ConfiguracaoPainel::get('logo_painel', ''),
            'logo_site'          => ConfiguracaoPainel::get('logo_site', ''),
        ];
        return view('admin.site.index', compact('config'));
    }

    public function update(Request $request)
    {
        $imagem = 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096';
        $request->validate([
            'sobre_foto'     => $imagem,
            'logo_painel'    => $imagem,
            'logo_site'      => $imagem,
            'banner1_imagem' => $imagem,
            'banner2_imagem' => $imagem,
        ]);

        $campos = [
            'banner1_titulo', 'banner1_subtitulo', 'banner1_eyebrow',
            'banner2_titulo', 'banner2_subtitulo', 'banner2_eyebrow',
            'info_titulo', 'info_subtitulo',
            'sobre_titulo', 'sobre_texto', 'sobre_pagina_texto',
            'cor_primaria', 'cor_secundaria',
        ];

        foreach ($campos as $campo) {
            if ($request->has($campo) && $request->input($campo) !== null) {
                ConfiguracaoPainel::set($campo, (string) $request->input($campo));
            }
        }

        $dirImg = public_path('traducaidiomas/img/');

        if (!is_dir($dirImg)) {
            mkdir($dirImg, 0775, true);
        }

        if ($request->hasFile('sobre_foto')) {
            $nome = Upload::salvar($request->file('sobre_foto'), 'img', Upload::IMAGENS, 'sobre');
            ConfiguracaoPainel::set('sobre_foto', $nome);
        }

        // Os logos mantêm nome fixo (.png); o conteúdo já foi validado como imagem acima.
        if ($request->hasFile('logo_painel')) {
            $file = $request->file('logo_painel');
            $file->move($dirImg, 'logo_painel.png');
            ConfiguracaoPainel::set('logo_painel', 'logo_painel.png');
        }

        if ($request->hasFile('logo_site')) {
            $file = $request->file('logo_site');
            $file->move($dirImg, 'logo_site.png');
            ConfiguracaoPainel::set('logo_site', 'logo_site.png');
        }

        if ($request->hasFile('banner1_imagem')) {
            $nome = Upload::salvar($request->file('banner1_imagem'), 'banners', Upload::IMAGENS, 'banner1');
            ConfiguracaoPainel::set('banner1_imagem', $nome);
        }

        if ($request->hasFile('banner2_imagem')) {
            $nome = Upload::salvar($request->file('banner2_imagem'), 'banners', Upload::IMAGENS, 'banner2');
            ConfiguracaoPainel::set('banner2_imagem', $nome);
        }

        return redirect()->route('admin.site.index')->with('success', 'Site atualizado com sucesso!');
    }
}
