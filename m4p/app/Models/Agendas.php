<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendas extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['agen_finicio','agen_ftermino','agen_nsobrecupo','pers_nrut_paciente','pers_nrut_medico','tiat_ncod','ceme_ncod','esag_ncod'];
}
