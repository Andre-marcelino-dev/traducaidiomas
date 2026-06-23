<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
   
   public function quiz()
    {
        return view('site.quiz.quiz');
    }
}
