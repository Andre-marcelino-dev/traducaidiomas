<?php

namespace App\Support;

use App\Models\Aluno;
use App\Models\Atividade;
use App\Models\Aula;
use App\Models\Materiais;
use App\Models\Matricula;
use App\Models\Modulo;
use App\Models\Notificacao;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Cria os avisos do sino do app (tabela tbl_notificacoes).
 *
 * O "link" é a tela do app que abre ao tocar no aviso (ex.: "/atividade?id=12").
 * Se algo der errado aqui, só registra no log: o cadastro do professor nunca
 * falha por causa de um aviso.
 */
class Notificar
{
    /** Professor cadastrou uma aula (só as ativas avisam). */
    public static function aulaNova(Aula $aula): void
    {
        if ($aula->status_aulas !== 'ATIVO') {
            return;
        }

        $quando = $aula->data_aulas ? ' — ' . date('d/m', strtotime((string) $aula->data_aulas)) : '';
        $quando .= $aula->hora_aulas ? ' às ' . substr((string) $aula->hora_aulas, 0, 5) : '';

        self::paraCurso(
            (int) $aula->id_curso,
            $aula->id_modulo,
            (int) $aula->id_professor,
            "Nova aula: {$aula->titulo_aulas}{$quando}",
            '/agenda'
        );
    }

    /** Professor publicou uma atividade. */
    public static function atividadeNova(Atividade $atividade): void
    {
        if (($atividade->status_atividade ?? 'ATIVA') !== 'ATIVA') {
            return;
        }

        $entrega = $atividade->data_entrega
            ? ' — entrega ' . date('d/m', strtotime((string) $atividade->data_entrega))
            : '';

        self::paraCurso(
            (int) $atividade->id_curso,
            $atividade->id_modulo,
            (int) $atividade->id_professor,
            "Nova atividade: {$atividade->titulo_atividade}{$entrega}",
            "/atividade?id={$atividade->id_atividade}"
        );
    }

    /** Professor enviou um material de apoio. */
    public static function materialNovo(Materiais $material): void
    {
        self::paraCurso(
            (int) $material->id_curso,
            $material->id_modulo,
            (int) $material->id_professor,
            "Novo material: {$material->titulo_materiais}",
            '/materiais',
            (int) $material->id_materiais
        );
    }

    /** Professor corrigiu a atividade do aluno. */
    public static function atividadeCorrigida(Atividade $atividade, int $idAluno, $nota, int $idProfessor): void
    {
        $textoNota = $nota !== null ? ': nota ' . str_replace('.', ',', rtrim(rtrim(number_format((float) $nota, 1, '.', ''), '0'), '.')) : '';

        self::paraAluno(
            $idAluno,
            $idProfessor,
            "Sua atividade \"{$atividade->titulo_atividade}\" foi corrigida{$textoNota}",
            "/atividade?id={$atividade->id_atividade}"
        );
    }

    /** Professor respondeu uma dúvida do aluno. */
    public static function duvidaRespondida(int $idAluno, string $assunto, int $idProfessor): void
    {
        self::paraAluno($idAluno, $idProfessor, "O professor respondeu sua dúvida: {$assunto}", '/duvida');
    }

    /** Professor aceitou ou recusou o pedido de reagendamento. */
    public static function reagendamentoRespondido(int $idAluno, string $tituloAula, bool $aceito, int $idProfessor): void
    {
        $resultado = $aceito ? 'foi confirmado' : 'foi recusado';
        self::paraAluno($idAluno, $idProfessor, "Seu pedido de reagendamento da aula \"{$tituloAula}\" {$resultado}", '/agenda');
    }

    /**
     * Alunos com matrícula ativa no curso (e, se o item é de um módulo, no
     * nível desse módulo). Aluno INATIVO não recebe.
     */
    public static function alunosDoCurso(int $idCurso, $idModulo = null): Collection
    {
        $consulta = Matricula::where('id_curso', $idCurso)->where('status_matricula', 'ATIVO');

        if ($idModulo) {
            $idNivel = Modulo::whereKey($idModulo)->value('id_nivel');
            if ($idNivel) {
                $consulta->where('id_nivel', $idNivel);
            }
        }

        $ids = $consulta->pluck('id_aluno')->unique();

        return Aluno::whereIn('id_aluno', $ids)
            ->where(fn ($q) => $q->whereNull('status_aluno')->orWhere('status_aluno', '!=', 'INATIVO'))
            ->pluck('id_aluno');
    }

    private static function paraCurso(int $idCurso, $idModulo, int $idProfessor, string $mensagem, string $link, ?int $idMaterial = null): void
    {
        try {
            $agora = now();
            $linhas = self::alunosDoCurso($idCurso, $idModulo)->map(fn ($idAluno) => [
                'id_aluno'                  => $idAluno,
                'id_professor'              => $idProfessor,
                'id_materiais'              => $idMaterial,
                'mensagem_notificacoes'     => $mensagem,
                'link_notificacoes'         => $link,
                'lida_notificacoes'         => false,
                'data_criacao_notificacoes' => $agora,
            ])->all();

            foreach (array_chunk($linhas, 500) as $lote) {
                Notificacao::insert($lote);
            }
        } catch (Throwable $e) {
            Log::warning('Falha ao criar notificações: ' . $e->getMessage(), ['curso' => $idCurso, 'link' => $link]);
        }
    }

    private static function paraAluno(int $idAluno, int $idProfessor, string $mensagem, string $link): void
    {
        try {
            Notificacao::create([
                'id_aluno'                  => $idAluno,
                'id_professor'              => $idProfessor,
                'mensagem_notificacoes'     => $mensagem,
                'link_notificacoes'         => $link,
                'lida_notificacoes'         => false,
                'data_criacao_notificacoes' => now(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Falha ao criar notificação: ' . $e->getMessage(), ['aluno' => $idAluno, 'link' => $link]);
        }
    }
}
