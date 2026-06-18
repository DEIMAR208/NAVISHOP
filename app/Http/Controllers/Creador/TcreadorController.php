<?php

namespace App\Http\Controllers\Creador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Catalogo;
use App\Models\Producto;

class TcreadorController extends Controller
{
    public function index()
    {
        // Obtenemos el primer negocio del usuario
        $user = auth()->user();
        $negocio = $user->negocios()->first(); // Obtener el primer negocio

        // Obtenemos los catálogos del negocio con sus productos
        $catalogos = $negocio
            ? Catalogo::where('negocio_id', $negocio->id)->with('productos')->latest()->get()
            : [];

        return Inertia::render('Creador/Tcreador', [
            'negocio_id' => $negocio ? $negocio->id : null,
            'negocio_nombre' => $negocio ? $negocio->nombre : 'Mi Negocio',
            'catalogos' => $catalogos
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'negocio_id' => 'required|exists:negocios,id',
            'productos' => 'required|array|min:1',
            'productos.*.nombre' => 'required|string|max:255',
            'productos.*.precio' => 'required|numeric|min:0',
            'productos.*.descripcion' => 'nullable|string',
        ]);

        $catalogo = Catalogo::create([
            'titulo' => $validated['titulo'],
            'user_id' => auth()->id(),
            'negocio_id' => $validated['negocio_id'],
            'secciones' => json_encode([]),
        ]);

        foreach ($validated['productos'] as $index => $producto) {
            $imagenPath = null;
            $imagenFile = $request->file("productos.{$index}.imagen");
            if ($imagenFile) {
                $imagenPath = $imagenFile->store('productos', 'public');
            }

            Producto::create([
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'descripcion' => $producto['descripcion'] ?? null,
                'imagen' => $imagenPath,
                'catalogo_id' => $catalogo->id,
            ]);
        }

        return back()->with('success', 'Catálogo creado exitosamente');
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'negocio_id' => 'required|exists:negocios,id',
            'productos' => 'nullable|array',
            'productos.*.id' => 'nullable',
            'productos.*.nombre' => 'required|string|max:255',
            'productos.*.precio' => 'required|numeric|min:0',
            'productos.*.descripcion' => 'nullable|string',
        ]);

        $catalogo = Catalogo::findOrFail($id);
        $catalogo->update([
            'titulo' => $validated['titulo'],
        ]);

        $sentProductIds = [];
        if (!empty($validated['productos'])) {
            foreach ($validated['productos'] as $index => $prodData) {
                if (!empty($prodData['id'])) {
                    $sentProductIds[] = $prodData['id'];
                }
            }
        }
        
        // Eliminar productos del catálogo que ya no estén en la lista recibida
        Producto::where('catalogo_id', $catalogo->id)
            ->whereNotIn('id', $sentProductIds)
            ->delete();

        if (!empty($validated['productos'])) {
            foreach ($validated['productos'] as $index => $prodData) {
                $imagenPath = null;
                $imagenFile = $request->file("productos.{$index}.imagen");
                if ($imagenFile) {
                    $imagenPath = $imagenFile->store('productos', 'public');
                }

                if (!empty($prodData['id'])) {
                    // Actualizar producto existente
                    $producto = Producto::findOrFail($prodData['id']);
                    $updateData = [
                        'nombre' => $prodData['nombre'],
                        'precio' => $prodData['precio'],
                        'descripcion' => $prodData['descripcion'] ?? null,
                    ];
                    if ($imagenPath) {
                        $updateData['imagen'] = $imagenPath;
                    }
                    $producto->update($updateData);
                } else {
                    // Crear nuevo producto
                    Producto::create([
                        'nombre' => $prodData['nombre'],
                        'precio' => $prodData['precio'],
                        'descripcion' => $prodData['descripcion'] ?? null,
                        'imagen' => $imagenPath,
                        'catalogo_id' => $catalogo->id,
                    ]);
                }
            }
        }

        return back()->with('success', 'Catálogo actualizado exitosamente');
    }

    public function destroy(string $id)
    {
        $catalogo = Catalogo::findOrFail($id);
        $catalogo->delete();
        return back()->with('success', 'Catálogo eliminado');
    }
}
