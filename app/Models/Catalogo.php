<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    protected $fillable = [
        'titulo',
        'user_id',
        'negocio_id',
        'secciones',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
