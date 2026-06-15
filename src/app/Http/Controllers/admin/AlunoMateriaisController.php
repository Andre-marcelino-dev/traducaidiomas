<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Materiais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriaisController extends Controller
{
    public function index(Request $request)
    {
        $query = Materiais::with(['professor', 'curso']);

        if ($request->filled('nivel')) {
            $query->where('nivel_material', $request->nivel);
        }

        if ($request->filled('id_curso')) {
            $query->where('id_curso', $request->id_curso);
        }

        if ($request->filled('busca')) {
            $query->where('titulo_materiais', 'like', '%' . $request->busca . '%');
        }

        $materiais = $query->latest('criado_em_materiais')->paginate(12);

        return view('admin.materiais.alunoindex', compact('materiais'));
    }

    public function show(Materiais $materiais)
    {
        $materiais->load(['professor', 'curso']);

        return view('admin.materiais.modal.showaluno', compact('materiais'));
    }

    public function download(Materiais $materiais)
    {
        if (!$materiais->arquivo_materiais || !Storage::disk('public')->exists($materiais->arquivo_materiais)) {
            return back()->with('error', 'Arquivo não encontrado.');
        }

        return Storage::disk('public')->download($materiais->arquivo_materiais, $materiais->titulo_materiais);
    }
}
