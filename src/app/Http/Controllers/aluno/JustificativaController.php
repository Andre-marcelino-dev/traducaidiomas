<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\JustificativaFalta;
use App\Models\Presenca;
use Illuminate\Http\Request;

class JustificativaController extends Controller
{
    /**
     * Aluno justifica uma falta que o professor lançou.
     */
    public function store(Request $request, $id_presenca)
    {
        $presenca = Presenca::where('id_aluno', auth('aluno')->id())->findOrFail($id_presenca);

        $request->validate([
            'motivo_justificativa' => 'required|string|min:10|max:1000',
        ], [
            'motivo_justificativa.required' => 'Escreva o motivo da falta.',
            'motivo_justificativa.min'      => 'O motivo deve ter ao menos 10 caracteres.',
            'motivo_justificativa.max'      => 'O motivo pode ter no máximo 1000 caracteres.',
        ]);

        if (!$presenca->ehFalta()) {
            return back()->with('error', 'Só é possível justificar aulas marcadas como falta.');
        }

        $emAnalise = JustificativaFalta::where('id_presenca', $presenca->id_presenca)->pendentes()->exists();
        if ($emAnalise) {
            return back()->with('error', 'Essa falta já tem uma justificativa em análise pelo professor.');
        }

        JustificativaFalta::create([
            'id_presenca'          => $presenca->id_presenca,
            'motivo_justificativa' => $request->motivo_justificativa,
            'status_justificativa' => 'pendente',
        ]);

        return back()->with('success', 'Justificativa enviada! O professor vai analisar e você verá a resposta aqui.');
    }
}
