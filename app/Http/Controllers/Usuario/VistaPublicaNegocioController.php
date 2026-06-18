<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use Inertia\Inertia;

class VistaPublicaNegocioController extends Controller
{
    public function show(string $id)
    {
        $negocio = Negocio::with(['promociones', 'catalogos.productos'])->findOrFail($id);

        return Inertia::render('Usuario/Fvista', [
            'negocio' => $negocio
        ]);
    }
}
