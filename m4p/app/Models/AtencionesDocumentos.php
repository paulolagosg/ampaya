<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtencionesDocumentos extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['atdo_tdocumento','atdo_fdocumento'];
}
