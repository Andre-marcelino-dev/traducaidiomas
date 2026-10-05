<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Salva arquivos enviados dentro de public/traducaidiomas/<pasta>.
 *
 * O nome do arquivo nunca vem do usuário: é gerado aqui, e a extensão só é
 * aceita se estiver na lista permitida. Isso impede que alguém envie um
 * "foto.php" (ou um PHP disfarçado de imagem) e consiga executá-lo no servidor.
 */
class Upload
{
    const IMAGENS = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    const DOCUMENTOS = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'zip'];
    // mpga/oga/weba/mp4: extensões que o PHP detecta pelo conteúdo de mp3/ogg/webm/m4a.
    const AUDIOS = ['mp3', 'mpga', 'wav', 'ogg', 'oga', 'm4a', 'mp4', 'aac', 'webm', 'weba'];

    /**
     * @return string nome do arquivo salvo (sem a pasta)
     */
    public static function salvar(UploadedFile $arquivo, string $pasta, array $permitidas, string $prefixo = 'arquivo'): string
    {
        $extensao = strtolower($arquivo->getClientOriginalExtension());

        if (!in_array($extensao, $permitidas, true)) {
            // Extensão do nome não é confiável: usa a detectada pelo conteúdo.
            $extensao = strtolower((string) $arquivo->extension());
        }

        if (!in_array($extensao, $permitidas, true)) {
            throw new \RuntimeException('Tipo de arquivo não permitido.');
        }

        $nome = Str::slug($prefixo) . '_' . time() . '_' . Str::random(8) . '.' . $extensao;
        $destino = public_path('traducaidiomas/' . $pasta);

        if (!is_dir($destino)) {
            mkdir($destino, 0755, true);
        }

        $arquivo->move($destino, $nome);

        $caminhoFinal = $destino . DIRECTORY_SEPARATOR . $nome;
        if (!file_exists($caminhoFinal)) {
            Log::error('Falha ao salvar upload: arquivo não encontrado após move()', [
                'destino' => $destino,
                'nome'    => $nome,
            ]);
            throw new \RuntimeException('Não foi possível salvar o arquivo no servidor.');
        }

        chmod($caminhoFinal, 0644);

        return $nome;
    }
}
