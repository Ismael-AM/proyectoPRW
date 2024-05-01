<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zapatilla extends Model
{
    use HasFactory;

    protected $timestamps = false;
    protected $table = 'zapatillas';
    protected $fillable = ['nombre', 'pvp', 'precio', 'imagen', 'id_marca', 'id_categoria'];
}
