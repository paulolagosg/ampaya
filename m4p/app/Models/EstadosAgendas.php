<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadosAgendas extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['esag_tnombre','esag_nestado'];
}
