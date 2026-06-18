<?php

namespace App\Http\Controllers\Creador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class PcreadorController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $mustVerifyEmail = $user instanceof MustVerifyEmail;
        $status = session('status');

        return Inertia::render('Creador/Pcreador', [
            'mustVerifyEmail' => $mustVerifyEmail,
            'status' => $status,
        ]);
    }
}