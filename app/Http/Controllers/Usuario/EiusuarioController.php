<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Contracts\Auth\MustVerifyEmail;

class EiusuarioController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $mustVerifyEmail = $user instanceof MustVerifyEmail;
        $status = session('status');

        if ($user && $user->role === 'creador') {
            return redirect()->route('creador.perfil');
        }

        return Inertia::render('Usuario/Usuariou', [
            'mustVerifyEmail' => $mustVerifyEmail,
            'status' => $status,
        ]);
    }
}
