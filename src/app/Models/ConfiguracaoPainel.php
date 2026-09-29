<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ConfiguracaoPainel extends Model
{
    protected $table = 'tbl_configuracoes_painel';
    protected $primaryKey = 'id_configuracoes_painel';
    public $timestamps = false;
    protected $fillable = [
        'chave_configuracoes_painel',
        'valor_configuracoes_painel',
    ];

    /** Nome da cópia das configurações guardada no container durante a requisição. */
    const CACHE = 'configuracoes_painel.todas';

    public static function get(string $chave, string $default = ''): string
    {
        return static::todas()[$chave] ?? $default;
    }

    public static function set(string $chave, ?string $valor): void
    {
        static::updateOrCreate(
            ['chave_configuracoes_painel' => $chave],
            ['valor_configuracoes_painel' => $valor]
        );

        app()->forgetInstance(self::CACHE);
    }

    /**
     * Todas as configurações numa consulta só, reaproveitada até o fim da requisição
     * (antes cada get() fazia uma consulta, e as telas chamam dezenas de vezes).
     */
    private static function todas(): array
    {
        if (!app()->bound(self::CACHE)) {
            app()->instance(self::CACHE, static::pluck('valor_configuracoes_painel', 'chave_configuracoes_painel')->all());
        }

        return app(self::CACHE);
    }
}
