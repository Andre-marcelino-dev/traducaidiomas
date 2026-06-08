<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashController extends Controller
{
    public function index()
    {
        $aluno = auth('aluno')->user();
        return view('aluno.dash.index', compact('aluno'));
    }
}
