<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;
use Inertia\Inertia;

class ProblemController extends Controller
{
    public function show(Problem $problem) {
        return Inertia::render('EditorPage');
    }
}
