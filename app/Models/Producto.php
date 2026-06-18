<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'catalogo_id',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function catalogo()
    {
        return $this->belongsTo(Catalogo::class);
    }
}