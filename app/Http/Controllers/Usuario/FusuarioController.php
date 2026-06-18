<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Negocio;

class FusuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user && $user->negocios()->exists()) {
            return Inertia::location(route('creador.dashboard'));
        }
        
        return Inertia::render('Usuario/Fusuario');
    }

    public function listado()
    {
        $negocios = Negocio::all();
        return Inertia::render('Usuario/NegociosCatalogo', [
            'negocios' => $negocios
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if ($user->negocios()->exists()) {
            return Inertia::location(route('creador.dashboard'));
        }
        
        $request->validate([
            'nombre' => 'required|string',
            'tipo' => 'required|string',
            'telefono' => 'required',
            'correo' => 'required|email',
            'direccion' => 'required',
            'foto_fachada' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $datos = $request->all();
        $datos['user_id'] = $user->id;

        if ($request->hasFile('foto_fachada')) {
            $datos['foto_fachada'] = $request->file('foto_fachada')->store('fachadas', 'public');
        }

        if ($request->hasFile('logo')) {
            $datos['logo'] = $request->file('logo')->store('logos', 'public');
        } else {
            $storagePath = storage_path('app/public/logos');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }
            $targetFile = $storagePath . '/defecto.png';
            if (!file_exists($targetFile)) {
                $sourceFile = public_path('iconos/defecto.png');
                if (file_exists($sourceFile)) {
                    copy($sourceFile, $targetFile);
                }
            }
            $datos['logo'] = 'logos/defecto.png';
        }

        $negocio = Negocio::create($datos);

        $user->update(['role' => 'creador']);

        return Inertia::location(route('creador.dashboard'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
