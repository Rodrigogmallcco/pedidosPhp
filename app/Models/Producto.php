<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Producto extends Model
{
    use HasFactory;
    protected $table = 'producto';
    protected $primaryKey = 'producto_id';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stockp',
        'codigo_uuid',
    ];
    
    public function detalles()
{
    return $this->hasMany(DetallePedido::class);
}

}

