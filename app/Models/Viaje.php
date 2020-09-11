<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Viaje extends Model
{
    protected $table = 'viajes';
    protected $fillable = [ 'fecha_viaje', 'pais', 'ciudad' ,'email' ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'email', 'email');
    }
}
