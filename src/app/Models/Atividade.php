<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
    protected $table = 'tbl_atividades';

    protected $primaryKey = 'id_atividade';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_professor',
        'id_curso',
        'id_modulo',
        'titulo_atividade',
        'descricao_atividade',
        'tipo_atividade',
        'audio',
        'arquivo_audio',
        'categoria_atividade',
        'finalidade_atividade',
        'data_entrega',
        'status_atividade',
    ];

    /**
     * Categorias utilizadas nos cards de atividades.
     */
    const CATEGORIAS = [
        'GRAMATICA' => [
            'label' => 'Gramática',
            'icone' => 'fa-book',
            'cor' => '#ef4444',
        ],

        'AUDIO' => [
            'label' => 'Áudio',
            'icone' => 'fa-headphones',
            'cor' => '#8b5cf6',
        ],

        'FALA' => [
            'label' => 'Fala',
            'icone' => 'fa-microphone',
            'cor' => '#06b6d4',
        ],

        'LEITURA' => [
            'label' => 'Leitura',
            'icone' => 'fa-book-open',
            'cor' => '#f59e0b',
        ],

        'ESCRITA' => [
            'label' => 'Escrita',
            'icone' => 'fa-pen',
            'cor' => '#10b981',
        ],

        'VOCABULARIO' => [
            'label' => 'Vocabulário',
            'icone' => 'fa-spell-check',
            'cor' => '#ec4899',
        ],
    ];

    /**
     * Finalidades disponíveis para a atividade.
     */
    const FINALIDADES = [
        'FIXACAO' => 'Exercício de fixação',
        'REVISAO' => 'Exercício de revisão',
        'AVALIACAO' => 'Avaliação',
    ];

    /**
     * Professor responsável pela atividade.
     */
    public function professor()
    {
        return $this->belongsTo(
            Professor::class,
            'id_professor',
            'id_professor'
        );
    }

    /**
     * Curso da atividade.
     */
    public function curso()
    {
        return $this->belongsTo(
            Curso::class,
            'id_curso',
            'id_curso'
        );
    }

    /**
     * Módulo da atividade.
     */
    public function modulo()
    {
        return $this->belongsTo(
            Modulo::class,
            'id_modulo',
            'id_modulo'
        );
    }

    /**
     * Questões da atividade.
     */
    public function questoes()
    {
        return $this->hasMany(
            AtividadeQuestao::class,
            'id_atividade',
            'id_atividade'
        );
    }

    /**
     * Respostas dos alunos.
     */
    public function respostas()
    {
        return $this->hasMany(
            AtividadeResposta::class,
            'id_atividade',
            'id_atividade'
        );
    }

    /**
     * Retorna as informações da categoria.
     *
     * @return array|null
     */
    public function categoriaInfo(): ?array
    {
        return self::CATEGORIAS[
            $this->categoria_atividade
        ] ?? null;
    }

    /**
     * Retorna o nome da finalidade.
     */
    public function finalidadeLabel(): ?string
    {
        return self::FINALIDADES[
            $this->finalidade_atividade
        ] ?? null;
    }
}