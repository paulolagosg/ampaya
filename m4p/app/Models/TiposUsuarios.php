<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposUsuarios extends Model
{
    use HasFactory;

    protected $fillable = [
        'tius_tnombre',
        'tius_nestado'
    ];
}
