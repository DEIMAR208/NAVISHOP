<?php

namespace App\Http\Controllers\Creador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Promocione;

class DcreadorController extends Controller
{
    public function index()
    {
        return Inertia::render('Creador/Dcreador');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'fechaInicio' => 'required',
            'fechaFin' => 'required',
            'descripcion' => 'required|string',
        ]);

        $negocio = auth()->user()->negocios()->first();

        $data = [
            'titulo' => $validated['titulo'],
            'foto' => $request->file('foto')->store('promociones', 'public'),
            'fecha_inicio' => $validated['fechaInicio'],
            'fecha_fin' => $validated['fechaFin'],
            'descripcion' => $validated['descripcion'],
            'negocio_id' => $negocio->id,
        ];

        Promocione::create($data);

        return back()->with('success', 'Promoción creada');
    }
}
