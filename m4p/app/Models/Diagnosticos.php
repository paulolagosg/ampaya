<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosticos extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['diag_tcodigo','diag_tdescripcion','diag_nestado'];
}
