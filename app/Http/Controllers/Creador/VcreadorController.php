<?php

namespace App\Http\Controllers\creador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Video;
use App\Models\Negocio;

class VcreadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $misNegocios = $user ? $user->negocios()->get() : collect([]);
        $misNegociosIds = $user ? $user->negocios()->pluck('id')->toArray() : [];
        $videos = Video::with('negocio')->latest()->get();

        return Inertia::render('Creador/Vcreador', [
            'videos' => $videos,
            'misNegocios' => $misNegocios,
            'misNegociosIds' => $misNegociosIds
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $misNegocios = $user ? $user->negocios()->get() : collect([]);

        return Inertia::render('Creador/VCcreador', [
            'misNegocios' => $misNegocios
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'link_video' => 'required|string|max:1000',
            'negocio_id' => 'required|exists:negocios,id',
            'es_promocion' => 'required|boolean',
            'fecha_inicio' => 'nullable|required_if:es_promocion,true|date',
            'fecha_fin' => 'nullable|required_if:es_promocion,true|date|after_or_equal:fecha_inicio',
        ]);

        $user = auth()->user();
        if (!$user->negocios()->where('id', $validated['negocio_id'])->exists()) {
            return back()->withErrors(['negocio_id' => 'No tienes permiso para agregar videos a este negocio.']);
        }

        Video::create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'link_video' => $validated['link_video'],
            'negocio_id' => $validated['negocio_id'],
            'es_promocion' => $validated['es_promocion'],
            'fecha_inicio' => $validated['es_promocion'] ? $validated['fecha_inicio'] : null,
            'fecha_fin' => $validated['es_promocion'] ? $validated['fecha_fin'] : null,
        ]);

        return back()->with('success', 'Video guardado exitosamente.');
    }
}
