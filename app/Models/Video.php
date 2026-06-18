<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'link_video',
        'negocio_id',
        'es_promocion',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }
}
