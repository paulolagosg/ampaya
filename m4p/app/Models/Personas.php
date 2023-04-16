<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personas extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable=['pers_nrut','pers_tdv','pers_tnombres','pers_tpaterno','pers_tmaterno','pers_tnombres','pers_tinfo','pers_nfono_fijo','pers_nfono_movil','pers_fnacimiento','pers_tcorreo','pers_tdireccion','pers_bpaciente','pers_tfirma','prev_ncod','espe_ncod','gene_ncod','ceme_ncod','pers_nestado'];
}
