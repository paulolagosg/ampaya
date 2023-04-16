<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atenciones extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['pers_nrut_paciente','pers_nrut_medico','aten_ffecha','aten_tsintomas','aten_tdiagnostico','aten_tindicaciones','aten_timagenes','aten_tlaboratorio','aten_tnuclear','aten_tfarmacos','esat_ncod','agen_ncod','aten_totros'];
}

