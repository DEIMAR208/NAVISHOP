<?php

namespace App\Http\Controllers\Creador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Promocione;

class CpromocionesController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $misNegociosIds = $user ? $user->negocios()->pluck('id')->toArray() : [];
        $promociones = Promocione::with('negocio')->latest()->get();
        
        return Inertia::render('Creador/Dpromociones', [
            'promociones' => $promociones,
            'misNegociosIds' => $misNegociosIds
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'titulo' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fechaInicio' => 'required',
            'fechaFin' => 'required',
            'descripcion' => 'required|string',
        ]);

        $promocion = Promocione::findOrFail($id);

        $data = [
            'titulo' => $validated['titulo'],
            'fecha_inicio' => $validated['fechaInicio'],
            'fecha_fin' => $validated['fechaFin'],
            'descripcion' => $validated['descripcion'],
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('promociones', 'public');
        }

        $promocion->update($data);

        return back()->with('success', 'Promoción actualizada con éxito');
    }

    public function destroy($id)
    {
        $promocion = Promocione::findOrFail($id);
        if ($promocion->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($promocion->foto);
        }
        $promocion->delete();

        return back()->with('success', 'Promoción eliminada con éxito');
    }
}
