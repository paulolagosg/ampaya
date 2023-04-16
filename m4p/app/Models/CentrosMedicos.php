<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentrosMedicos extends Model
{
    use HasFactory;

    protected $fillable = [
        'ceme_tnombre','ceme_tdescripcion','ceme_tdireccion','ceme_nfono_fijo','ceme_nfono_movil','ceme_tlogo','ceme_nestado'
    ];
}
