<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonasDocumentos extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['pers_nrut','pedo_tdocumento','pedo_fdocumento','tido_ncod'];
}
