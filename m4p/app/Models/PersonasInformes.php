<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonasInformes extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['pers_nrut','pein_tinforme','pein_finforme','tiin_ncod', 'pers_nrut_medico'];
}
