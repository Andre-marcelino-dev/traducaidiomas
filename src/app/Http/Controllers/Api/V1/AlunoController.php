<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Aluno;
use App\Models\AtividadeResposta;
use App\Models\AtividadeRespostaQuestao;
use App\Models\Feedback;
use App\Models\Matricula;
use App\Models\Notificacao;
use App\Models\Reagendamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlunoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $alunos = Aluno::query()
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status_aluno', $request->status);
            })
            ->orderBy('nome_aluno')
            ->get()
            ->makeHidden('senha_aluno');

        return response()->json([
            'success' => true,
            'data' => $alunos
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $aluno = Aluno::findOrFail($id)->makeHidden('senha_aluno');

        return response()->json([
            'success' => true,
            'data' => $aluno
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nome_aluno'      => 'required|string|max:255',
            'email_aluno'     => 'required|email|unique:tbl_alunos,email_aluno',
            'senha_aluno'     => 'required|string|min:6|confirmed',
            'telefone_aluno'  => 'required|string|max:20',
            'curso_aluno'     => 'required|string|max:100',
            'data_nasc_aluno' => 'required|date',
            'nivel_aluno'     => 'required|string|max:50',
            'status_aluno'    => 'required|string|max:50',
            'foto_aluno'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nome_aluno', 'email_aluno', 'telefone_aluno',
            'curso_aluno', 'data_nasc_aluno', 'nivel_aluno', 'status_aluno',
        ]);
        $data['senha_aluno'] = bcrypt($request->senha_aluno);
        $data['foto_aluno'] = $request->hasFile('foto_aluno')
            ? $this->salvarFotoAluno($request)
            : '';

        $aluno = Aluno::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Aluno cadastrado com sucesso!',
            'data' => $aluno->makeHidden('senha_aluno')
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $aluno = Aluno::findOrFail($id);

        $request->validate([
            'nome_aluno'      => 'required|string|max:255',
            'email_aluno'     => 'required|email|unique:tbl_alunos,email_aluno,' . $id . ',id_aluno',
            'senha_aluno'     => 'nullable|string|min:6|confirmed',
            'telefone_aluno'  => 'required|string|max:20',
            'curso_aluno'     => 'required|string|max:100',
            'data_nasc_aluno' => 'required|date',
            'nivel_aluno'     => 'required|string|max:50',
            'status_aluno'    => 'required|string|max:50',
            'foto_aluno'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nome_aluno', 'email_aluno', 'telefone_aluno',
            'curso_aluno', 'data_nasc_aluno', 'nivel_aluno', 'status_aluno',
        ]);

        if ($request->filled('senha_aluno')) {
            $data['senha_aluno'] = bcrypt($request->senha_aluno);
        }

        if ($request->hasFile('foto_aluno')) {
            $data['foto_aluno'] = $this->salvarFotoAluno($request);
        }

        $aluno->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Aluno atualizado com sucesso!',
            'data' => $aluno->fresh()->makeHidden('senha_aluno')
        ]);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status_aluno' => 'required|string|max:50',
        ]);

        $aluno = Aluno::findOrFail($id);
        $aluno->status_aluno = $request->status_aluno;
        $aluno->save();

        return response()->json([
            'success' => true,
            'message' => 'Status atualizado!',
            'data' => $aluno->makeHidden('senha_aluno')
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $aluno = Aluno::findOrFail($id);

        DB::transaction(function () use ($aluno, $id) {
            $respostaIds = AtividadeResposta::where('id_aluno', $id)->pluck('id_resposta');
            AtividadeRespostaQuestao::whereIn('id_resposta', $respostaIds)->delete();
            AtividadeResposta::where('id_aluno', $id)->delete();

            Notificacao::where('id_aluno', $id)->delete();
            Reagendamento::where('aluno_id', $id)->delete();
            Agenda::where('id_aluno', $id)->delete();
            Matricula::where('id_aluno', $id)->delete();
            Feedback::where('id_aluno', $id)->delete();

            DB::table('tbl_presenca')->where('id_aluno', $id)->delete();
            DB::table('tbl_progresso_materiais')->where('id_aluno', $id)->delete();

            $aluno->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Aluno excluído com sucesso!'
        ]);
    }

    private function salvarFotoAluno(Request $request): string
    {
        $foto = $request->file('foto_aluno');
        $nome = strtolower(str_replace(' ', '-', $request->nome_aluno)) . '.' . strtolower($foto->getClientOriginalExtension());
        $destino = base_path('traducaidiomas/alunos');

        if (!is_dir($destino)) {
            mkdir($destino, 0755, true);
        }
        chmod($destino, 0755);

        $foto->move($destino, $nome);

        $caminhoFinal = $destino . DIRECTORY_SEPARATOR . $nome;

        if (!file_exists($caminhoFinal)) {
            Log::error('Falha ao salvar foto do aluno: arquivo não encontrado após move()', [
                'destino' => $destino,
                'nome'    => $nome,
            ]);
            throw new \RuntimeException('Não foi possível salvar a foto do aluno no servidor.');
        }

        chmod($caminhoFinal, 0644);

        return $nome;
    }
}