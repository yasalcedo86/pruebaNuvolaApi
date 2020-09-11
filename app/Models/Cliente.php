<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $fillable = [ 'nombre', 'telefono', 'email', 'direccion', 'foto' ];

    public function viajes()
    {
        return $this->hasMany(Viaje::class, 'email', 'email');
    }
}

