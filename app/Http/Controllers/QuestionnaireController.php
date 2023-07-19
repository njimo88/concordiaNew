<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index()
    {
        return view('N_questionnaire');
    }

    public function result()
    {
        
        return view('result');
    }

}

