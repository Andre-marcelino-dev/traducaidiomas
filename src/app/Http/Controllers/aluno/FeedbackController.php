<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $aluno = auth('aluno')->user();

        $request->validate([
            'id_curso'     => 'required|exists:tbl_cursos,id_curso',
            'id_professor' => 'required|exists:tbl_professor,id_professor',
            'nota'         => 'required|integer|min:1|max:5',
            'comentario'   => 'nullable|string|max:500',
        ]);

        Feedback::updateOrCreate(
            [
                'id_aluno' => $aluno->id_aluno,
                'id_curso' => $request->id_curso,
            ],
            [
                'id_professor' => $request->id_professor,
                'nota'         => $request->nota,
                'comentario'   => $request->comentario,
            ]
        );

        return back()->with('success', 'Feedback enviado com sucesso! Obrigado pela sua avaliação.');
    }
}
