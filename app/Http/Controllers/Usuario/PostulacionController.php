<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostulacionController extends Controller
{
    public function create()
    {
        return Inertia::render('Usuario/Postular');
    }

    public function store(Request $request)
    {
        return back();
    }
}
