<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;
    protected $table = 'rol'; 

    protected $primaryKey = 'rol_id'; 
    public $timestamps = false;

    protected $fillable = [
        'nombreDelRol',
    ];
    public function usuarioRol()
    {
        return $this->hasMany(UsuarioRol::class, 'rol_id', 'rol_id');

    }
}
