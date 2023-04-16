<?php

namespace App\Http\Controllers;

use App\Models\Especialidades;
use App\Models\Generos;
use App\Models\Personas;
use App\Models\Previsiones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PersonasController extends Controller
{
    
     public function __construct(){
        $this->middleware('auth');
    }

    public function lista()
    {
        
        $nCentroMedico = Auth::user()->ceme_ncod;

        $personas = Personas::orderBy('pers_nrut')
                    ->select(DB::raw("concat(pers_tnombres,' ',pers_tpaterno) as nombres"),DB::raw("case when pers_ntipo_docto = 1 then concat(pers_nrut,'-',pers_tdv) else pers_nrut end as rut"),'pers_tcorreo',DB::raw("case when pers_nfono_fijo is not null and pers_nfono_movil is not null then concat(pers_nfono_fijo,' - ',pers_nfono_movil) when pers_nfono_fijo is null and pers_nfono_movil is not null then concat(pers_nfono_movil) when pers_nfono_movil is null and pers_nfono_fijo is not null then concat(pers_nfono_fijo) end as fono"),DB::raw("case when pers_nestado = 1 then 'Activo' else 'Inactivo' end as estado"),'pers_ncod','pers_nestado')
                    ->where('pers_bpaciente','=',1)
                    ->where('ceme_ncod','=',$nCentroMedico)
                    ->get();

        return view ('personas.lista',compact('personas'));
    }

    public function crear()
    {
        $generos = Generos::orderBy('gene_tnombre')->where('gene_nestado','=',1)->get();
        $especialidades = Especialidades::orderBy('espe_tnombre')->where('espe_nestado','=',1)->get();
        $previsiones = Previsiones::orderBy('prev_tnombre')->where('prev_nestado','=',1)->get();
        
        return view ('personas.crear', compact('generos','especialidades','previsiones'));
    }
    public function agregar(Request $request)
    {
        
        $nTipoDocto = $request->pers_ntipo_docto;
        $nEsPaciente = 1;
        $nEspecialidad = 0;
        $nCentroMedico = Auth::user()->ceme_ncod;

        if(isset($request->pers_bpaciente)){
            $nEsPaciente = $request->pers_bpaciente;
        }

        if(isset($request->espe_ncod)){
            $nEsPaciente = $request->espe_ncod;
        }



        if($nTipoDocto == 1){
            $this->validate($request,[
                //'rut' => 'required|min:5|valida_rut',
                'rut' => 'required|min:5',
                'pers_tnombres' => 'required',
                'pers_tpaterno' => 'required',
                'pers_tnombres' => 'required',
                'pers_fnacimiento' => 'required|date',
                //'pers_tcorreo' => 'required|email|unique:personas',
                'pers_tcorreo' => 'required|email',
                'pers_tdireccion' => 'required',
                'gene_ncod' => 'required',
                'prev_ncod' => 'required',
            ]);
            $explode = explode("-",$request->rut);
            $pers_nrut = $explode[0];
            $pers_tdv = $explode[1];
        }
        else{
            $this->validate($request,[
                'rut' => 'required|min:5',
                'pers_tnombres' => 'required',
                'pers_tpaterno' => 'required',
                'pers_tnombres' => 'required',
                'pers_fnacimiento' => 'required|date',
                //'pers_tcorreo' => 'required|email|unique:personas',
                'pers_tcorreo' => 'required|email',
                'pers_tdireccion' => 'required',
                'gene_ncod' => 'required',
                'prev_ncod' => 'required',
            ]);
            $pers_nrut = $request->rut;
            $pers_tdv = "";
        }
        //existe rut
        $nExisteRut = Personas::where('pers_nrut','=',$pers_nrut)->count();
        if(intval($nExisteRut > 0)){
          return Redirect::back()->withInput($request->input())->withErrors(['RUT/DNI'=>'El RUT ingresado ya existe']);
        }


        //al menos un fono de contacto
        if(strlen($request->pers_nfono_fijo) == 0 && strlen($request->pers_nfono_movil) == 0){
          return Redirect::back()->withInput($request->input())->withErrors(['Teléfono'=>'Debe ingresar al menos un teléfono']);
        }
        else{//tipo de dato fono
        	if(
            	(strlen($request->pers_nfono_fijo) < 9 && is_numeric($request->pers_nfono_fijo) == 0 && $request->pers_nfono_fijo !="") 
            	|| 
            	(strlen($request->pers_nfono_movil) < 9 && is_numeric($request->pers_nfono_movil) == 0 && $request->pers_nfono_movil !="")
        	){
          		return Redirect::back()->withInput($request->input())->withErrors(['Teléfono'=>'Formato de teléfono no válido, debe ser numérico y de 9 dígitos']);
        	}
        }


        $paciente = new Personas();
        $paciente->pers_nrut = $pers_nrut;
        $paciente->pers_tdv = $pers_tdv;
        $paciente->pers_tinfo = '';
        $paciente->pers_tfirma= '';
        $paciente->pers_tnombres = $request->pers_tnombres;
        $paciente->pers_tpaterno= $request->pers_tpaterno;
        $paciente->pers_tmaterno = $request->pers_tmaterno;
        $paciente->pers_nfono_fijo = $request->pers_nfono_fijo;
        $paciente->pers_nfono_movil = $request->pers_nfono_movil;
        $paciente->pers_fnacimiento = $request->pers_fnacimiento;
        $paciente->pers_tcorreo = $request->pers_tcorreo;
        $paciente->pers_tdireccion = $request->pers_tdireccion;
        $paciente->pers_bpaciente = $nEsPaciente;
        $paciente->prev_ncod = $request->prev_ncod;
        $paciente->gene_ncod = $request->gene_ncod;
        $paciente->espe_ncod = $nEspecialidad;
        $paciente->ceme_ncod = $nCentroMedico;
        $paciente->pers_nestado = 1;
        $paciente->pers_ntipo_docto = $nTipoDocto;
        $paciente->save();

        return redirect()->route('personas.lista')->with('message','Registro creado!');

    }

    public function editar($id){
        $nCentroMedico = Auth::user()->ceme_ncod;

        try{
            $datos = Personas::where('pers_ncod', '=', $id)
                            ->where('ceme_ncod','=',$nCentroMedico)
                            ->firstOrFail();
        }
        catch(ModelNotFoundException $e){
            abort(404, __('Sorry, the page you are looking for is not available.'));    
        }

        $datos = Personas::where('pers_ncod','=',$id)
            ->where('ceme_ncod','=',$nCentroMedico)
            ->select('pers_tnombres','pers_tpaterno','pers_tmaterno','pers_ntipo_docto','pers_tcorreo','pers_nfono_fijo','pers_nfono_movil','pers_nestado','pers_ncod',DB::raw("case when pers_ntipo_docto = 1 then concat(pers_nrut,'-',pers_tdv) else pers_nrut end as rut"),'gene_ncod','prev_ncod','pers_tdireccion','pers_fnacimiento','pers_ncod')
            ->get();

        $generos = Generos::orderBy('gene_tnombre')->where('gene_nestado','=',1)->get();
        $especialidades = Especialidades::orderBy('espe_tnombre')->where('espe_nestado','=',1)->get();
        $previsiones = Previsiones::orderBy('prev_tnombre')->where('prev_nestado','=',1)->get();

        return view('personas.editar',compact('datos','generos','especialidades','previsiones'));
    }

    public function modificar(Request $request)
    {
        $nTipoDocto = $request->pers_ntipo_docto;
        $nEsPaciente = 1;
        $nEspecialidad = 0;
        $nCentroMedico = Auth::user()->ceme_ncod;

        if(isset($request->pers_bpaciente)){
            $nEsPaciente = $request->pers_bpaciente;
        }

        if(isset($request->espe_ncod)){
            $nEsPaciente = $request->espe_ncod;
        }



        if($nTipoDocto == 1){
            $this->validate($request,[
//                'rut' => 'required|min:5|valida_rut',
                'rut' => 'required|min:5',
                'pers_tnombres' => 'required',
                'pers_tpaterno' => 'required',
                'pers_tnombres' => 'required',
                'pers_fnacimiento' => 'required|date',
                'pers_tcorreo' => 'required|email',
                'pers_tdireccion' => 'required',
                'gene_ncod' => 'required',
                'prev_ncod' => 'required',
            ]);
            $explode = explode("-",$request->rut);
            $pers_nrut = $explode[0];
            $pers_tdv = $explode[1];
        }
        else{
            $this->validate($request,[
                'rut' => 'required|min:5',
                'pers_tnombres' => 'required',
                'pers_tpaterno' => 'required',
                'pers_tnombres' => 'required',
                'pers_fnacimiento' => 'required|date',
                'pers_tcorreo' => 'required|email',
                'pers_tdireccion' => 'required',
                'gene_ncod' => 'required',
                'prev_ncod' => 'required',
            ]);
            $pers_nrut = $request->rut;
            $pers_tdv = "";
        }
        //existe rut
        $nExisteRut = Personas::where('pers_nrut','=',$pers_nrut)
                    ->where('pers_ncod','<>',$request->pers_ncod)
                    ->where('ceme_ncod','=',$nCentroMedico)
                    ->count();
        if(intval($nExisteRut > 0)){
          return Redirect::back()->withInput($request->input())->withErrors(['RUT/DNI'=>'El RUT ingresado ya existe']);
        }

        $nExisteCorreo = Personas::where('pers_tcorreo','=',$request->pers_tcorreo)
                    ->where('pers_ncod','<>',$request->pers_ncod)
                    ->where('ceme_ncod','=',$nCentroMedico)
                    ->count();
        if(intval($nExisteCorreo > 0)){
          return Redirect::back()->withInput($request->input())->withErrors(['RUT/DNI'=>'El correo electrónico ingresado ya existe']);
        }

        //al menos un fono de contacto
    

		if(strlen($request->pers_nfono_fijo) == 0 && strlen($request->pers_nfono_movil) == 0){
          return Redirect::back()->withInput($request->input())->withErrors(['Teléfono'=>'Debe ingresar al menos un teléfono']);
        }
        else{//tipo de dato fono
        	if(
            	(strlen($request->pers_nfono_fijo) < 9 && is_numeric($request->pers_nfono_fijo) == 0 && $request->pers_nfono_fijo !="") 
            	|| 
            	(strlen($request->pers_nfono_movil) < 9 && is_numeric($request->pers_nfono_movil) == 0 && $request->pers_nfono_movil !="")
        	){
          		return Redirect::back()->withInput($request->input())->withErrors(['Teléfono'=>'Formato de teléfono no válido, debe ser numérico y de 9 dígitos']);
        	}
        }
		
		$updateFijo = "";
		if($request->pers_nfono_fijo != ""){
			$updateFijo = ", pers_nfono_fijo = ".$request->pers_nfono_fijo;
		}

		$updateMovil = "";
		if($request->pers_nfono_movil != ""){
			$updateMovil = ", pers_nfono_movil = ".$request->pers_nfono_movil;
		}

        $sqlUpdate = "update personas set pers_nrut = ".$pers_nrut.", pers_tdv='".$pers_tdv."', pers_tnombres = '".$request->pers_tnombres."',pers_tpaterno= '".$request->pers_tpaterno."' ,pers_tmaterno = '".$request->pers_tmaterno."' ".$updateFijo.$updateMovil." , pers_fnacimiento = '".$request->pers_fnacimiento."', pers_tcorreo = '".$request->pers_tcorreo."', pers_tdireccion = '".$request->pers_tdireccion."', prev_ncod = ".$request->prev_ncod.", gene_ncod = ".$request->gene_ncod.", pers_ntipo_docto = $nTipoDocto where pers_ncod = ".$request->pers_ncod." ";
        
        //echo $sqlUpdate;exit;

        DB::beginTransaction();

        try {
            DB::statement($sqlUpdate);

            DB::commit();
            return redirect()->route('personas.lista')->with('message','Registro modificado!');
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->withInput($request->input())->withErrors(['Error'=>'Ocurrió un error al intentar modificar, favor intente nuevamente'.$e]);
        }

        

    }

    public function eliminar($id){
        try{
            $datos = Personas::where('pers_ncod', '=', $id)->firstOrFail();
        }
        catch(ModelNotFoundException $e){
            abort(404, __('Sorry, the page you are looking for is not available.'));    
        }

        $sqlUpdate = "update personas set pers_nestado = 0 where pers_ncod = ".$id." ";
        
        //echo $sqlUpdate;exit;

        DB::beginTransaction();

        try {
            DB::statement($sqlUpdate);

            DB::commit();
            return redirect()->route('personas.lista')->with('message','Registro modificado!');
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->withInput($request->input())->withErrors(['Error'=>'Ocurrió un error al intentar modificar, favor intente nuevamente']);
        }

    }

    public function activar($id){
        try{
            $datos = Personas::where('pers_ncod', '=', $id)->firstOrFail();
        }
        catch(ModelNotFoundException $e){
            abort(404, __('Sorry, the page you are looking for is not available.'));    
        }

        $sqlUpdate = "update personas set pers_nestado = 1 where pers_ncod = ".$id." ";
        
        //echo $sqlUpdate;exit;

        DB::beginTransaction();

        try {
            DB::statement($sqlUpdate);

            DB::commit();
            return redirect()->route('personas.lista')->with('message','Registro modificado!');
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->withInput($request->input())->withErrors(['Error'=>'Ocurrió un error al intentar modificar, favor intente nuevamente']);
        }

    }

}
