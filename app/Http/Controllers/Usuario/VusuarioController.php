<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Video;

class VusuarioController extends Controller
{
    public function index()
    {
        $videos = Video::with('negocio')->latest()->get();
        return Inertia::render('Usuario/Vusuario', [
            'videos' => $videos
        ]);
    }
}
