<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagenologias extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['iman_tcodigo','iman_tnombre','iman_nestado'];
}
