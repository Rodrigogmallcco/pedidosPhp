<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    
    protected $table = 'usuario'; 

    protected $primaryKey = 'usuario_id'; 

    protected $fillable = [
        'persona_id', 
        'username',
        'password',
        'correoElectronico',
        'estado',
    ];

    
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'persona_id');
    }

    
    public function usuarioRol()
    {
        return $this->hasMany(UsuarioRol::class, 'usuario_id', 'usuario_id');
    }
    public function pedidos()
    {
        return $this->hasMany(Pedido::class,'usuario_id','usuario_id');
    }
}
