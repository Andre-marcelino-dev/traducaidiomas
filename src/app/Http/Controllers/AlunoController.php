<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlunoController extends Controller
{
    public function index()
    {
        $alunos = DB::table('tbl_alunos')->get();
        return view('alunos', compact('alunos'));
    }
}