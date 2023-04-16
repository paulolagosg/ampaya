<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendasBloqueos extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['agbl_finicio','agbl_ftermino','pers_nrut_medico','ceme_ncod','egbl_ncod'];
}
