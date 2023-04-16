<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposPacientes extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['tipa_tnombre','tipa_nestado'];
}
