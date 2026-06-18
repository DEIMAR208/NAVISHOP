<?php

namespace App\Http\Controllers\Creador;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use Inertia\Inertia;

class VistaNegocioController extends Controller
{
    public function show(string $id)
    {
        $negocio = Negocio::with(['promociones', 'catalogos.productos'])->findOrFail($id);

        return Inertia::render('Creador/VIstacreador', [
            'negocio' => $negocio,
            'esCreador' => true
        ]);
    }
}
