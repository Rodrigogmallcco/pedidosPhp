<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $table = 'persona'; 

    protected $primaryKey = 'persona_id'; 

    public $timestamps = false; 

    protected $fillable = [
        'nombre',
        'direccion',
        'tipoDocumento',
        'numeroDocumento',
    ];
    public function usuario()
{
    return $this->hasOne(Usuario::class, 'persona_id', 'persona_id');
}

}
