<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    protected $fillable = [
        'nombre', 'tipo', 'foto_fachada', 'logo', 'nit', 'telefono',
        'correo', 'direccion', 'google_maps', 'dias_trabajo',
        'hora_apertura', 'hora_cierre', 'facebook', 'instagram', 'tiktok', 'user_id'
    ];

    protected $casts = [
        'dias_trabajo' => 'array',
    ];

    public function promociones()
    {
        return $this->hasMany(Promocione::class);
    }

    public function catalogos()
    {
        return $this->hasMany(Catalogo::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
