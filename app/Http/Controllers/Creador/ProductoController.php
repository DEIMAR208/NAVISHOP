<?php

namespace App\Http\Controllers\Creador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'catalogo_id' => 'required|exists:catalogos,id',
            'imagen' => 'nullable|image|max:5120',
        ]);

        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create([
            'nombre' => $validated['nombre'],
            'precio' => $validated['precio'],
            'descripcion' => $validated['descripcion'],
            'catalogo_id' => $validated['catalogo_id'],
            'imagen' => $imagenPath,
        ]);

        return back()->with('success', 'Producto añadido exitosamente');
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|max:5120',
        ]);

        $producto->nombre = $validated['nombre'];
        $producto->precio = $validated['precio'];
        $producto->descripcion = $validated['descripcion'];

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $producto->imagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto->save();

        return back()->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }
        $producto->delete();

        return back()->with('success', 'Producto eliminado exitosamente');
    }
}
