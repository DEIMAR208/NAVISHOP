<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Promocione;
use Inertia\Inertia;

class PusuarioController extends Controller
{
    public function index()
    {
        $promociones = Promocione::with('negocio')
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        return Inertia::render('Usuario/Pusuario', [
            'promociones' => $promociones,
        ]);
    }
}
