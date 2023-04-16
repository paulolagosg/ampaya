<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Previsiones extends Model
{
    use HasFactory;

    protected $fillable = ['prev_tnombre','prev_nestado'];
}
