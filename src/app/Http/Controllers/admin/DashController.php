<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use Illuminate\Http\Request;

class DashController extends Controller
{
    public function index()
    {
        $professor      = auth('admin')->user();
        $totalProfessores = Professor::count();
        $professoresRecentes = Professor::orderBy('criado_em_professor', 'desc')->take(5)->get();

        return view('admin.dash.dashboard', compact(
            'professor',
            'totalProfessores',
            'professoresRecentes'
        ));
    }
}