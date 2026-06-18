<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocione extends Model
{
    protected $fillable = [
        'titulo', 'foto', 'fecha_inicio', 'fecha_fin', 'descripcion', 'negocio_id'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }
}
