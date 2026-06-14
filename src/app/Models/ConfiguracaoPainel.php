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

    public static function get(string $chave, string $default = ''): string
    {
        $config = static::where('chave_configuracoes_painel', $chave)->first();
        return $config ? $config->valor_configuracoes_painel : $default;
    }

    public static function set(string $chave, ?string $valor): void
    {
        static::updateOrCreate(
            ['chave_configuracoes_painel' => $chave],
            ['valor_configuracoes_painel' => $valor]
        );
    }
}
