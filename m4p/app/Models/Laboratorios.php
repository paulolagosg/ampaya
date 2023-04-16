<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorios extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['labo_tcodigo','labo_tnombre','labo_nestado'];
}
