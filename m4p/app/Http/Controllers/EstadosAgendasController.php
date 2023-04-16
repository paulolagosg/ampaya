<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstadosAgendasController extends Controller
{
    public function lista(){
        $estados = EstadosAgendas::orderBy('esag_tnombre')->get();
    }
}
