<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $table = 'persona'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'personaId'; // Clave primaria de la tabla

    public $timestamps = false; // Si no necesitas las marcas de tiempo created_at y updated_at

    protected $fillable = [
        'nombre',
        'direccion',
        'tipoDocumento',
        'numeroDocumento',
    ];
    public function usuario()
{
    return $this->hasOne(Usuario::class, 'personaId', 'personaId');
}

}
