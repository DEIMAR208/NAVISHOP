<?php

namespace App\Http\Controllers\Creador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Negocio;

class CreadorDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $misNegocios = $user ? $user->negocios()->get() : collect([]);
        $todosNegocios = Negocio::all();

        return Inertia::render('Creador/Creador', [
            'misNegocios' => $misNegocios,
            'todosNegocios' => $todosNegocios
        ]);
    }
}
