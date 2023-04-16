<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposInformes extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['tiin_tnombre','tiin_nestado'];
}
