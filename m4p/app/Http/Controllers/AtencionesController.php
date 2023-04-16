<?php

namespace App\Http\Controllers;

use App\Models\Agendas;
use App\Models\Atenciones;
use App\Models\AtencionesDocumentos;
use App\Models\CentrosMedicos;
use App\Models\Diagnosticos;
use App\Models\Imagenologias;
use App\Models\Laboratorios;
use App\Models\Nucleares;
use App\Models\Personas;
use File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use PDF;
use Mail;

class AtencionesController extends Controller
{
    public function lista(){

        $nCentroMedico = Auth::user()->ceme_ncod;
        $nTipoUsuario = Auth::user()->tius_ncod;
        $nIDUsuario = Auth::user()->id;

        if($nTipoUsuario == 2){
            $atenciones = Atenciones::join('personas','personas.pers_nrut','=','atenciones.pers_nrut_medico')
                ->join('personas as pa','pa.pers_nrut','=','atenciones.pers_nrut_paciente')
                ->join('estados_atenciones','atenciones.esat_ncod','=','estados_atenciones.esat_ncod')
                ->join('agendas','agendas.agen_ncod','=','atenciones.agen_ncod')
                ->where('personas.user_id','=',$nIDUsuario)
                ->where('personas.ceme_ncod','=',$nCentroMedico)
                ->where('pa.ceme_ncod','=',$nCentroMedico)
				->orderBy('aten_ncod','DESC')
                ->select(DB::raw("case when pa.pers_ntipo_docto = 1 then concat(pa.pers_nrut,'-',pa.pers_tdv) else pa.pers_nrut end as rut"),DB::raw("concat(pa.pers_tnombres,' ',pa.pers_tpaterno) as nombre"),'esat_tnombre',DB::raw("date_format(agen_finicio,'%d/%m/%Y %H:%i') as aten_ffecha"),'aten_ncod',DB::raw("date_format(agen_finicio,'%d/%m/%Y') as agen_finicio"))
                ->get();
        }
        else{
            $atenciones = Atenciones::join('personas','personas.pers_nrut','=','atenciones.pers_nrut_medico')
                ->join('personas as pa','pa.pers_nrut','=','atenciones.pers_nrut_paciente')
                ->join('agendas','agendas.agen_ncod','=','atenciones.agen_ncod')
                ->join('estados_atenciones','atenciones.esat_ncod','=','estados_atenciones.esat_ncod')
                ->where('personas.ceme_ncod','=',$nCentroMedico)
                ->where('pa.ceme_ncod','=',$nCentroMedico)
                ->where('atenciones.esat_ncod','=',2)
				->orderBy('aten_ncod','DESC')
                ->select(DB::raw("case when pa.pers_ntipo_docto = 1 then concat(pa.pers_nrut,'-',pa.pers_tdv) else pa.pers_nrut end as rut"),DB::raw("concat(pa.pers_tnombres,' ',pa.pers_tpaterno) as nombre"),'esat_tnombre',DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno) as medico"),DB::raw("date_format(agen_finicio,'%d/%m/%Y %H:%i') as aten_ffecha"),'aten_ncod','agen_finicio')
                ->get();
        }


        return view('atenciones.lista',compact('atenciones','nTipoUsuario'));

    }

    public function lista_paciente($id){

        $nCentroMedico = Auth::user()->ceme_ncod;
        $nTipoUsuario = Auth::user()->tius_ncod;
        $nIDUsuario = Auth::user()->id;

        if($nTipoUsuario == 2){
            $atenciones = Atenciones::join('personas','personas.pers_nrut','=','atenciones.pers_nrut_medico')
                ->join('personas as pa','pa.pers_nrut','=','atenciones.pers_nrut_paciente')
                ->join('estados_atenciones','atenciones.esat_ncod','=','estados_atenciones.esat_ncod')
                ->join('agendas','agendas.agen_ncod','=','atenciones.agen_ncod')
                ->where('personas.user_id','=',$nIDUsuario)
                ->where('personas.ceme_ncod','=',$nCentroMedico)
                ->where('pa.ceme_ncod','=',$nCentroMedico)
                ->where('pa.pers_ncod','=',$id)
                ->select(DB::raw("case when pa.pers_ntipo_docto = 1 then concat(pa.pers_nrut,'-',pa.pers_tdv) else pa.pers_nrut end as rut"),DB::raw("concat(pa.pers_tnombres,' ',pa.pers_tpaterno) as nombre"),'esat_tnombre',DB::raw("date_format(agen_finicio,'%d/%m/%Y %H:%i') as aten_ffecha"),'aten_ncod')
				->orderBy('aten_ncod','DESC')
                ->get();
        }
        else{
            $atenciones = Atenciones::join('personas','personas.pers_nrut','=','atenciones.pers_nrut_medico')
                ->join('personas as pa','pa.pers_nrut','=','atenciones.pers_nrut_paciente')
                ->join('agendas','agendas.agen_ncod','=','atenciones.agen_ncod')
                ->join('estados_atenciones','atenciones.esat_ncod','=','estados_atenciones.esat_ncod')
                ->where('personas.ceme_ncod','=',$nCentroMedico)
                ->where('pa.ceme_ncod','=',$nCentroMedico)
                ->where('atenciones.esat_ncod','=',2)
                ->where('pa.pers_ncod','=',$id)
				->orderBy('aten_ncod','DESC')
                ->select(DB::raw("case when pa.pers_ntipo_docto = 1 then concat(pa.pers_nrut,'-',pa.pers_tdv) else pa.pers_nrut end as rut"),DB::raw("concat(pa.pers_tnombres,' ',pa.pers_tpaterno) as nombre"),'esat_tnombre',DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno) as medico"),DB::raw("date_format(agen_finicio,'%d/%m/%Y %H:%i') as aten_ffecha"),'aten_ncod')
                ->get();
        }


        return view('atenciones.lista_paciente',compact('atenciones','nTipoUsuario'));

    }

    public function ver($id){
        $nCentroMedico = Auth::user()->ceme_ncod;

        try{
            $datos = Atenciones::join('personas','personas.pers_nrut','=','atenciones.pers_nrut_paciente')
                            ->where('aten_ncod', '=', $id)
                            ->where('personas.ceme_ncod','=',$nCentroMedico)
                            ->firstOrFail();
            
        }
        catch(ModelNotFoundException $e){
            return Redirect::back()->withErrors(['Agenda'=>'No existe una atencion asociada']);  
        }


        $datos_atencion = Atenciones::join('personas','atenciones.pers_nrut_paciente','=', 'personas.pers_nrut')
            ->join('agendas','agendas.agen_ncod','=','atenciones.agen_ncod')
            ->where('atenciones.aten_ncod','=',$id)
            ->where('personas.ceme_ncod','=',$nCentroMedico)
            ->select(DB::raw("concat(pers_tnombres,' ',pers_tpaterno,' ',coalesce(pers_tmaterno,'')) as nombres"),
            DB::raw("case when pers_ntipo_docto = 1 then concat(pers_nrut,'-',pers_tdv) else pers_nrut end as rut"),'pers_tcorreo',
            DB::raw("case when pers_nfono_fijo is not null and pers_nfono_movil is not null then concat(pers_nfono_fijo,' - ',pers_nfono_movil) when pers_nfono_fijo is null and pers_nfono_movil is not null then concat(pers_nfono_movil) when pers_nfono_movil is null and pers_nfono_fijo is not null then concat(pers_nfono_fijo) end as fono"),
            DB::raw("case when pers_nestado = 1 then 'Activo' else 'Inactivo' end as estado"),'pers_ncod','pers_nestado','aten_tdiagnostico'
            ,'aten_tlaboratorio','aten_tnuclear','aten_tfarmacos','aten_timagenes','aten_tsintomas'
            //,DB::raw("case when personas.ceme_ncod = 2 then concat(coalesce(aten_tindicaciones,''),'<br>',coalesce(aten_tlaboratorio,''),'<br>',coalesce(aten_tnuclear,''),'<br>',coalesce(aten_timagenes,''),'<br>',coalesce(aten_tfarmacos,''))  else aten_tindicaciones end as aten_tindicaciones")
            ,'aten_tindicaciones'
            ,'aten_totros','pers_tinfo',
            DB::raw("YEAR(CURDATE())-YEAR(pers_fnacimiento) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(pers_fnacimiento,'%m-%d'), 0 , -1 ) AS edad"),
            DB::raw("DATE_FORMAT(agen_finicio, '%d/%m/%Y %H:%i') as agen_finicio"),'aten_tnuclear_otros','aten_tima_otros', 'aten_tlabo_otros')
            ->get();

        $diagnosticos = array();
        if(isset($datos_atencion[0]->aten_tdiagnostico) && $datos_atencion[0]->aten_tdiagnostico !=""){
        $diagnosticos = Diagnosticos::whereRaw("diag_ncod in (".$datos_atencion[0]->aten_tdiagnostico.")")
            ->get();
        }

        $laboratorios = array();
        if(isset($datos_atencion[0]->aten_tlaboratorio) && strpos($datos_atencion[0]->aten_tlaboratorio, '<') === false && $datos_atencion[0]->aten_tlaboratorio !=""){
            $laboratorios = Laboratorios::whereRaw("labo_ncod in (".$datos_atencion[0]->aten_tlaboratorio.")")
                ->get();
        }
        
        $nucleares = array();
        if(isset($datos_atencion[0]->aten_tnuclear) && strpos($datos_atencion[0]->aten_tnuclear, '<') === false && $datos_atencion[0]->aten_tnuclear !=""){
            $nucleares = Nucleares::whereRaw("nucl_ncod in (".$datos_atencion[0]->aten_tnuclear.")")
                ->get();
        }

        $imagenes = array();
        if(isset($datos_atencion[0]->aten_timagenes) && strpos($datos_atencion[0]->aten_timagenes, '<') === false && $datos_atencion[0]->aten_timagenes !=""){
            $imagenes = Imagenologias::whereRaw("iman_ncod in (".$datos_atencion[0]->aten_timagenes.")")
                ->get();
        }

        $documentos = AtencionesDocumentos::where('aten_ncod','=',$id)
                    ->select('atdo_ncod','aten_ncod','atdo_tdocumento','atdo_fdocumento',DB::raw("SUBSTRING_INDEX(atdo_tdocumento,'_', -1) as nombre"))
                    ->get();

        return view('atenciones.ver',compact('datos_atencion','diagnosticos','laboratorios','nucleares','imagenes','documentos','id'));

    }

    public function ver_paciente($id){
        $nCentroMedico = Auth::user()->ceme_ncod;

        try{
            $datos = Atenciones::join('personas','personas.pers_nrut','=','atenciones.pers_nrut_paciente')
                            ->where('aten_ncod', '=', $id)
                            ->where('personas.ceme_ncod','=',$nCentroMedico)
                            ->firstOrFail();
            
        }
        catch(ModelNotFoundException $e){
            return Redirect::back()->withErrors(['Agenda'=>'No existe una atencion asociada']);  
        }


        $datos_atencion = Atenciones::join('personas','atenciones.pers_nrut_paciente','=', 'personas.pers_nrut')
            ->join('agendas','agendas.agen_ncod','=','atenciones.agen_ncod')
            ->where('atenciones.aten_ncod','=',$id)
            ->where('personas.ceme_ncod','=',$nCentroMedico)
            ->select(DB::raw("concat(pers_tnombres,' ',pers_tpaterno,' ',coalesce(pers_tmaterno,'')) as nombres"),
            DB::raw("case when pers_ntipo_docto = 1 then concat(pers_nrut,'-',pers_tdv) else pers_nrut end as rut"),'pers_tcorreo',
            DB::raw("case when pers_nfono_fijo is not null and pers_nfono_movil is not null then concat(pers_nfono_fijo,' - ',pers_nfono_movil) when pers_nfono_fijo is null and pers_nfono_movil is not null then concat(pers_nfono_movil) when pers_nfono_movil is null and pers_nfono_fijo is not null then concat(pers_nfono_fijo) end as fono"),
            DB::raw("case when pers_nestado = 1 then 'Activo' else 'Inactivo' end as estado"),'pers_ncod','pers_nestado','aten_tdiagnostico'
            ,'aten_tlaboratorio','aten_tnuclear','aten_tfarmacos','aten_timagenes','aten_tsintomas'
            //,DB::raw("case when personas.ceme_ncod = 2 then concat(coalesce(aten_tindicaciones,''),'<br>',coalesce(aten_tlaboratorio,''),'<br>',coalesce(aten_tnuclear,''),'<br>',coalesce(aten_timagenes,''),'<br>',coalesce(aten_tfarmacos,''))  else aten_tindicaciones end as aten_tindicaciones")
            ,'aten_tindicaciones'
            ,'aten_totros','pers_tinfo',
            DB::raw("YEAR(CURDATE())-YEAR(pers_fnacimiento) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(pers_fnacimiento,'%m-%d'), 0 , -1 ) AS edad"),
            DB::raw("DATE_FORMAT(agen_finicio, '%d/%m/%Y %H:%i') as agen_finicio"),'aten_tnuclear_otros','aten_tima_otros', 'aten_tlabo_otros')
            ->get();

        $diagnosticos = array();
        if(isset($datos_atencion[0]->aten_tdiagnostico) && $datos_atencion[0]->aten_tdiagnostico !=""){
        $diagnosticos = Diagnosticos::whereRaw("diag_ncod in (".$datos_atencion[0]->aten_tdiagnostico.")")
            ->get();
        }

        $laboratorios = array();
        if(isset($datos_atencion[0]->aten_tlaboratorio) && strpos($datos_atencion[0]->aten_tlaboratorio, '<') === false && $datos_atencion[0]->aten_tlaboratorio !=""){
            $laboratorios = Laboratorios::whereRaw("labo_ncod in (".$datos_atencion[0]->aten_tlaboratorio.")")
                ->get();
        }
        
        $nucleares = array();
        if(isset($datos_atencion[0]->aten_tnuclear) && strpos($datos_atencion[0]->aten_tnuclear, '<') === false && $datos_atencion[0]->aten_tnuclear !=""){
            $nucleares = Nucleares::whereRaw("nucl_ncod in (".$datos_atencion[0]->aten_tnuclear.")")
                ->get();
        }

        $imagenes = array();
        if(isset($datos_atencion[0]->aten_timagenes) && strpos($datos_atencion[0]->aten_timagenes, '<') === false && $datos_atencion[0]->aten_timagenes !=""){
            $imagenes = Imagenologias::whereRaw("iman_ncod in (".$datos_atencion[0]->aten_timagenes.")")
                ->get();
        }

        $documentos = AtencionesDocumentos::where('aten_ncod','=',$id)
                    ->select('atdo_ncod','aten_ncod','atdo_tdocumento','atdo_fdocumento',DB::raw("SUBSTRING_INDEX(atdo_tdocumento,'_', -1) as nombre"))
                    ->get();

        return view('atenciones.ver_paciente',compact('datos_atencion','diagnosticos','laboratorios','nucleares','imagenes','documentos','id'));

    }

    public function atender($id){

        $nCentroMedico = Auth::user()->ceme_ncod;
        $nIDUsuario = Auth::user()->id;

        //DB::enableQueryLog(); // Enable query log
       
        try{
            $datos = Agendas::where('agen_ncod', '=', $id)->firstOrFail();
            
        }
        catch(ModelNotFoundException $e){
            return Redirect::back()->withErrors(['Agenda'=>'No existe una cita asociada']);  
        }
        $nAtencion = 0;
        $existeAtencion = Atenciones::where('agen_ncod', '=', $id)->get();
        
        foreach($existeAtencion as $dato){
            $nAtencion = $dato->aten_ncod;
        }
        
        $medico = $datos->pers_nrut_medico;
        $paciente = $datos->pers_nrut_paciente;

        //dd(DB::getQueryLog()); // Show results of log
        if(intval($nAtencion) == 0){
            
            $atencion = new Atenciones();
            $atencion->pers_nrut_medico = $medico;
            $atencion->pers_nrut_paciente = $paciente;
            $atencion->aten_ffecha = now();
            $atencion->esat_ncod = 1;
            $atencion->agen_ncod = $id;
            
            
            
			DB::beginTransaction();

            try {
                $atencion->save();
				$nAtencion = $atencion->id;
            	//$nAtencion = $atencion->aten_ncod;
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }            
            
            
           
        }
        $datos_paciente = Personas::where('pers_nrut','=',$paciente)
                        ->where('ceme_ncod','=',$nCentroMedico)
                        ->select(DB::raw("case when pers_ntipo_docto = 1 then concat(pers_nrut,'-',pers_tdv) else pers_nrut end as rut"),DB::raw("concat(pers_tnombres,' ',pers_tpaterno,' ',coalesce(pers_tmaterno,'')) as nombre"),DB::raw("YEAR(CURDATE())-YEAR(pers_fnacimiento) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(pers_fnacimiento,'%m-%d'), 0 , -1 ) AS edad"),'pers_nrut','pers_tinfo')
                        ->get();

        $diagnosticos = Diagnosticos::where('diag_nestado','=',1)
                        ->select('diag_ncod',DB::raw("upper(diag_tcodigo) as diag_tcodigo"),DB::raw("upper(diag_tdescripcion) as diag_tdescripcion"))
                        ->groupBy('diag_ncod',DB::raw("upper(diag_tcodigo)"),DB::raw("upper(diag_tdescripcion)"))
                        ->get();

        $laboratorios = Laboratorios::where('labo_nestado','=',1)
                        ->select('labo_ncod',DB::raw("upper(labo_tcodigo) as labo_tcodigo"),DB::raw("upper(labo_tnombre) as labo_tnombre"))
                        ->groupBy('labo_ncod',DB::raw("upper(labo_tcodigo)"),DB::raw("upper(labo_tnombre)"))
                        ->get();

        $imagenes = Imagenologias::where('iman_nestado','=',1)
                        ->select('iman_ncod',DB::raw("upper(iman_tcodigo) as iman_tcodigo"),DB::raw("upper(iman_tnombre) as iman_tnombre"))
                        ->groupBy('iman_ncod',DB::raw("upper(iman_tcodigo)"),DB::raw("upper(iman_tnombre)"))
                        ->get();                        

        $nucleares = Nucleares::where('nucl_nestado','=',1)
                        ->select('nucl_ncod',DB::raw("upper(nucl_tcodigo) as nucl_tcodigo"),DB::raw("upper(nucl_tnombre) as nucl_tnombre"))
                        ->groupBy('nucl_ncod',DB::raw("upper(nucl_tcodigo)"),DB::raw("upper(nucl_tnombre)"))
                        ->get();      

        $documentos = AtencionesDocumentos::where('aten_ncod','=',$nAtencion)
                    ->select('atdo_ncod','aten_ncod','atdo_tdocumento','atdo_fdocumento',DB::raw("SUBSTRING_INDEX(atdo_tdocumento,'_', -1) as nombre"))
                    ->get();

        $datos_atencion = Atenciones::where('aten_ncod','=',$nAtencion)
                        ->get();
        
        $atenciones = Atenciones::join('personas','personas.pers_nrut','=','atenciones.pers_nrut_medico')
                ->join('personas as pa','pa.pers_nrut','=','atenciones.pers_nrut_paciente')
                ->join('estados_atenciones','atenciones.esat_ncod','=','estados_atenciones.esat_ncod')
                ->join('agendas','agendas.agen_ncod','=','atenciones.agen_ncod')
                ->where('personas.user_id','=',$nIDUsuario)
                ->where('personas.ceme_ncod','=',$nCentroMedico)
                ->where('pa.ceme_ncod','=',$nCentroMedico)
                ->where('pa.pers_nrut','=',$paciente)
                ->where('atenciones.aten_ncod','<>',$nAtencion)
                ->select(DB::raw("case when pa.pers_ntipo_docto = 1 then concat(pa.pers_nrut,'-',pa.pers_tdv) else pa.pers_nrut end as rut"),DB::raw("concat(pa.pers_tnombres,' ',pa.pers_tpaterno) as nombre"),'esat_tnombre',
                DB::raw("date_format(agen_finicio,'%d/%m/%Y %H:%i') as aten_ffecha"),'aten_ncod','aten_tnuclear_otros','aten_tima_otros', 'aten_tlabo_otros')
                ->get();

        return view('atenciones.atender',compact('nAtencion','datos_paciente','diagnosticos','laboratorios','imagenes','nucleares','documentos','datos_atencion','atenciones'));


    }

    public function guardar(Request $request){
        $files = $request->file('documentos');

        //dd($request);
        $fFechaActual = date('Ymd_His');

        $aFormatosPermitidos = array('csv','txt');
        
        $tDiagnosticos = "";
        $tLaboratorios = "";
        $tImagenes = "";
        $tNucleares = "";
        $nFinalizar = 1;
        
        if(isset($request->finalizar)){
            $nFinalizar = 2;
        }

        if(isset($request->aten_tdiagnostico)){
            foreach ($request->aten_tdiagnostico as $diagnosticos) {
                if($tDiagnosticos == ""){
                    $tDiagnosticos .= $diagnosticos;
                }
                else{
                    $tDiagnosticos .= ",".$diagnosticos;
                }
            }
        }

        if(isset($request->aten_tlaboratorio)){
            foreach ($request->aten_tlaboratorio as $laboratorios) {
                if($tLaboratorios == ""){
                    $tLaboratorios .= $laboratorios;
                }
                else{
                    $tLaboratorios .= ",".$laboratorios;
                }
            }
        }

        if(isset($request->aten_timagenes)){
            foreach ($request->aten_timagenes as $imagenes) {
                if($tImagenes == ""){
                    $tImagenes .= $imagenes;
                }
                else{
                    $tImagenes .= ",".$imagenes;
                }
            }
        }
        
        if(isset($request->aten_tnuclear)){
            foreach ($request->aten_tnuclear as $nucleares) {
                if($tNucleares == ""){
                    $tNucleares .= $nucleares;
                }
                else{
                    $tNucleares .= ",".$nucleares;
                }
            }
        }


        $sqlPersona = "update personas set pers_tinfo='".$request->pers_tinfo."' where pers_nrut = ".$request->pers_nrut;





        $sqlAtencion = "update atenciones set aten_tsintomas='".$request->aten_tsintomas."', aten_tdiagnostico='".$tDiagnosticos."', aten_totros = '".$request->otros."',aten_tlaboratorio='".$tLaboratorios."', aten_timagenes = '".$tImagenes."', aten_tnuclear = '".$tNucleares."', aten_tfarmacos='".$request->aten_tfarmacos."', aten_tindicaciones='".$request->aten_tindicaciones."'
        , esat_ncod = ".$nFinalizar." , aten_tnuclear_otros = '".$request->aten_tnuclear_otros."', aten_tima_otros = '".$request->aten_tima_otros."', aten_tlabo_otros = '".$request->aten_tlabo_otros."'
        where aten_ncod = ".$request->aten_ncod;
	
		//echo $sqlAtencion ;exit;

        $bArchivosOK = true;
        // $path = storage_path('app/public/');

        // if($request->hasFile('documentos')){
        //     foreach ($files as $file) {

        //         $fileName = time().rand(0, 1000)."_".pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        //         $fileName = $this->clean($fileName);
        //         $fileName = $fileName.'.'.$file->getClientOriginalExtension();

        //         $bArchivosOK = $bArchivosOK && $file->move($path, $fileName);

        //         $sqlDocumentos = "insert into atenciones_documentos (aten_ncod,atdo_tdocumento,atdo_fdocumento) values (".$request->aten_ncod.",'".$fileName."',now())";
        //         DB::statement($sqlDocumentos);
        //         //Storage::disk('local')->put($file,'Contents');
        //     }
        // }

        if($bArchivosOK){
            DB::beginTransaction();

            try {
                DB::statement($sqlPersona);
                DB::statement($sqlAtencion);

                DB::commit();
                return Redirect::back()->with('message','Registro modificado!');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withInput($request->input())->withErrors(['Error'=>'Ocurrió un error al intentar modificar, favor intente nuevamente']);
            }
        }
        else{
            $sqlDocumentosEliminar = "delete from atenciones_documentos where aten_ncod = ".$request->aten_ncod." ";
            DB::statement($sqlDocumentosEliminar);

            return Redirect::back()->withInput($request->input())->withErrors(['Archivos'=>'No fue posible cargar el/los archivo/s, favor intente nuevamente']);
        }

    }

    public function eliminar_documento($id,$docto){
        $path = storage_path('app/public/');
        
        if(file_exists($path.$docto)){
            unlink($path.$docto);
        }

        $sqlEliminar = "delete from atenciones_documentos where atdo_ncod = ".$id;
        DB::statement($sqlEliminar);

        return Redirect::back()->with('message','Documento eliminado!');

    }

    public function generarPDF($id){
        try{
            $datos = Atenciones::where('aten_ncod', '=', $id)->firstOrFail();
            
        }
        catch(ModelNotFoundException $e){
            return Redirect::back()->withErrors(['Agenda'=>'No existe una atencion asociada']);  
        }

        $nCentroMedico = Auth::user()->ceme_ncod;
        $centro_medico = CentrosMedicos::where('ceme_ncod','=',$nCentroMedico)
                    ->get();

        $datos_atencion = Atenciones::join('personas','atenciones.pers_nrut_paciente','=', 'personas.pers_nrut')
            ->join('agendas','agendas.agen_ncod','=','atenciones.agen_ncod')
            ->where('atenciones.aten_ncod','=',$id)
            ->where('personas.ceme_ncod','=',$nCentroMedico)
            ->select(DB::raw("concat(pers_tnombres,' ',pers_tpaterno,' ',coalesce(pers_tmaterno,'')) as nombres"),DB::raw("case when pers_ntipo_docto = 1 then concat(pers_nrut,'-',pers_tdv) else pers_nrut end as rut"),'pers_tcorreo'
            ,DB::raw("case when pers_nfono_fijo is not null and pers_nfono_movil is not null then concat(pers_nfono_fijo,' - ',pers_nfono_movil) when pers_nfono_fijo is null and pers_nfono_movil is not null then concat(pers_nfono_movil) when pers_nfono_movil is null and pers_nfono_fijo is not null then concat(pers_nfono_fijo) end as fono")
            ,DB::raw("case when pers_nestado = 1 then 'Activo' else 'Inactivo' end as estado"),'pers_ncod','pers_nestado'
            ,'aten_tdiagnostico','aten_tlaboratorio','aten_tnuclear','aten_tfarmacos','aten_timagenes','aten_tsintomas','aten_tindicaciones','aten_totros','pers_tinfo'
            ,DB::raw("YEAR(CURDATE())-YEAR(pers_fnacimiento) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(pers_fnacimiento,'%m-%d'), 0 , -1 ) AS edad")
            ,DB::raw("DATE_FORMAT(agen_finicio, '%d/%m/%Y %H:%i') as agen_finicio"),'pers_tdireccion'
            ,'aten_tlabo_otros','aten_tima_otros','aten_tnuclear_otros')
            ->get();

        $diagnosticos = array();
        if(isset($datos_atencion[0]->aten_tdiagnostico) && $datos_atencion[0]->aten_tdiagnostico != ""){
        $diagnosticos = Diagnosticos::whereRaw("diag_ncod in (".$datos_atencion[0]->aten_tdiagnostico.")")
            ->get();
        }

        $laboratorios = array();
        if(isset($datos_atencion[0]->aten_tlaboratorio)  && strpos($datos_atencion[0]->aten_tlaboratorio, '<') === false  && $datos_atencion[0]->aten_tlaboratorio != ""){
            $laboratorios = Laboratorios::whereRaw("labo_ncod in (".$datos_atencion[0]->aten_tlaboratorio.")")
                ->get();
        }
        
        $nucleares = array();
        if(isset($datos_atencion[0]->aten_tnuclear) && strpos($datos_atencion[0]->aten_tnuclear, '<') === false && $datos_atencion[0]->aten_tnuclear != "" ){
            $nucleares = Nucleares::whereRaw("nucl_ncod in (".$datos_atencion[0]->aten_tnuclear.")")
                ->get();
        }

        $imagenes = array();
        if(isset($datos_atencion[0]->aten_timagenes) && strpos($datos_atencion[0]->aten_timagenes, '<') === false && $datos_atencion[0]->aten_timagenes != ""){
            $imagenes = Imagenologias::whereRaw("iman_ncod in (".$datos_atencion[0]->aten_timagenes.")")
                ->get();
        }

        $documentos = AtencionesDocumentos::where('aten_ncod','=',$id)
                    ->select('atdo_ncod','aten_ncod','atdo_tdocumento','atdo_fdocumento',DB::raw("SUBSTRING_INDEX(atdo_tdocumento,'_', -1) as nombre"))
                    ->get();

        $data = [
            'centro_medico' => $centro_medico,
            'datos_atencion' => $datos_atencion,
            'diagnosticos' => $diagnosticos ,
            'laboratorios' => $laboratorios,
            'nucleares' => $nucleares,
            'imagenes' => $imagenes 
        ]; 

        $pdf = PDF::loadView('atenciones.verPDF', $data);
        return $pdf->stream('CertificadoAtencion_'.$id.'.pdf');

    }

    public function receta($id){
        $fechaActual = date('Ymd_His');

        try{
            $datos = Atenciones::where('aten_ncod', '=', $id)->firstOrFail();
            
        }
        catch(ModelNotFoundException $e){
            return Redirect::back()->withErrors(['Agenda'=>'No existe una atencion asociada']);  
        }

        $nCentroMedico = Auth::user()->ceme_ncod;
        $centro_medico = CentrosMedicos::where('ceme_ncod','=',$nCentroMedico)
                    ->get();
		DB::enableQueryLog(); 
        $datos_atencion = Atenciones::join('personas','atenciones.pers_nrut_paciente','=', 'personas.pers_nrut')
            ->join('agendas','agendas.agen_ncod','=','atenciones.agen_ncod')
            ->join('personas as m','m.pers_nrut','=','atenciones.pers_nrut_medico')
            ->where('atenciones.aten_ncod','=',$id)
            ->where('personas.ceme_ncod','=',$nCentroMedico)
            ->select(DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno,' ',coalesce(personas.pers_tmaterno,'')) as nombres"),
            DB::raw("case when personas.pers_ntipo_docto = 1 then concat(personas.pers_nrut,'-',personas.pers_tdv) else personas.pers_nrut end as rut"),'personas.pers_tcorreo',
            DB::raw("case when personas.pers_nfono_fijo is not null and personas.pers_nfono_movil is not null then concat(personas.pers_nfono_fijo,' - ',personas.pers_nfono_movil) when personas.pers_nfono_fijo is null and personas.pers_nfono_movil is not null then concat(personas.pers_nfono_movil) when personas.pers_nfono_movil is null and personas.pers_nfono_fijo is not null then concat(personas.pers_nfono_fijo) end as fono"),
            DB::raw("case when personas.pers_nestado = 1 then 'Activo' else 'Inactivo' end as estado"),'personas.pers_ncod','personas.pers_nestado',
            'aten_tdiagnostico','aten_tlaboratorio','aten_tnuclear','aten_tfarmacos','aten_timagenes','aten_tsintomas',
            'aten_tindicaciones','aten_tlabo_otros','aten_tima_otros','aten_tnuclear_otros'
            ,'aten_totros','personas.pers_tinfo',
            DB::raw("YEAR(CURDATE())-YEAR(personas.pers_fnacimiento) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(personas.pers_fnacimiento,'%m-%d'), 0 , -1 ) AS edad"),DB::raw("DATE_FORMAT(agen_finicio, '%d/%m/%Y') as agen_finicio"),'personas.pers_tdireccion','atenciones.pers_nrut_medico','m.pers_tfirma','personas.pers_ntipo_docto','personas.pers_ntipo_docto')
            ->get();
		//dd(DB::getQueryLog());
        $diagnosticos = array();
        if(isset($datos_atencion[0]->aten_tdiagnostico) && $datos_atencion[0]->aten_tdiagnostico !=""){
        $diagnosticos = Diagnosticos::whereRaw("diag_ncod in (".$datos_atencion[0]->aten_tdiagnostico.")")
            ->get();
        }
		//dd($datos_atencion[0]);
        $laboratorios = array();
       //echo "-->".strpos($datos_atencion[0]->aten_tlaboratorio, '<');exit;
//        if(isset($datos_atencion[0]->aten_tlaboratorio) && strpos($datos_atencion[0]->aten_tlaboratorio, '<') === false && $datos_atencion[0]->aten_tlaboratorio != ""){
        if(isset($datos_atencion[0]->aten_tlaboratorio)  && $datos_atencion[0]->aten_tlaboratorio != "" ){
            $laboratorios = Laboratorios::whereRaw("labo_ncod in (".$datos_atencion[0]->aten_tlaboratorio.")")
                ->get();
        }
        
        $nucleares = array();
        if(isset($datos_atencion[0]->aten_tnuclear) && strpos($datos_atencion[0]->aten_tnuclear, '<') === false  && $datos_atencion[0]->aten_tnuclear != ""){
            $nucleares = Nucleares::whereRaw("nucl_ncod in (".$datos_atencion[0]->aten_tnuclear.")")
                ->get();
        }

        $imagenes = array();
        if(isset($datos_atencion[0]->aten_timagenes) && strpos($datos_atencion[0]->aten_timagenes, '<') === false  && $datos_atencion[0]->aten_timagenes != ""){
            $imagenes = Imagenologias::whereRaw("iman_ncod in (".$datos_atencion[0]->aten_timagenes.")")
                ->get();
        }

        $documentos = AtencionesDocumentos::where('aten_ncod','=',$id)
                    ->select('atdo_ncod','aten_ncod','atdo_tdocumento','atdo_fdocumento',DB::raw("SUBSTRING_INDEX(atdo_tdocumento,'_', -1) as nombre"))
                    ->get();
		
        $data = [
            'centro_medico' => $centro_medico,
            'datos_atencion' => $datos_atencion,
            'diagnosticos' => $diagnosticos ,
            'laboratorios' => $laboratorios,
            'nucleares' => $nucleares,
            'imagenes' => $imagenes 
        ]; 
        
        
        $tamanio = array(0,0,368.504,595.276);
        $pdf = PDF::loadView('atenciones.receta', $data)->setPaper($tamanio);
        return $pdf->stream('receta_'.$fechaActual.'_'.$id.'.pdf');
        
        //return view('atenciones.receta', $data);


    }

    public function enviar($id){
        $fechaActual = date('Ymd_His');

        try{
            $datos = Atenciones::where('aten_ncod', '=', $id)->firstOrFail();
            
        }
        catch(ModelNotFoundException $e){
            return Redirect::back()->withErrors(['Agenda'=>'No existe una atencion asociada']);  
        }

        $nCentroMedico = Auth::user()->ceme_ncod;
        $centro_medico = CentrosMedicos::where('ceme_ncod','=',$nCentroMedico)
                    ->get();
	 	
        $datos_atencion = Atenciones::join('personas','atenciones.pers_nrut_paciente','=', 'personas.pers_nrut')
            ->join('agendas','agendas.agen_ncod','=','atenciones.agen_ncod')
            ->join('personas as m','m.pers_nrut','=','atenciones.pers_nrut_medico')
            ->where('atenciones.aten_ncod','=',$id)
            ->where('personas.ceme_ncod','=',$nCentroMedico)
            ->select(DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno,' ',coalesce(personas.pers_tmaterno,'')) as nombres"),
            DB::raw("case when personas.pers_ntipo_docto = 1 then concat(personas.pers_nrut,'-',personas.pers_tdv) else personas.pers_nrut end as rut"),'personas.pers_tcorreo',
            DB::raw("case when personas.pers_nfono_fijo is not null and personas.pers_nfono_movil is not null then concat(personas.pers_nfono_fijo,' - ',personas.pers_nfono_movil) when personas.pers_nfono_fijo is null and personas.pers_nfono_movil is not null then concat(personas.pers_nfono_movil) when personas.pers_nfono_movil is null and personas.pers_nfono_fijo is not null then concat(personas.pers_nfono_fijo) end as fono"),
            DB::raw("case when personas.pers_nestado = 1 then 'Activo' else 'Inactivo' end as estado"),'personas.pers_ncod','personas.pers_nestado',
            'aten_tdiagnostico','aten_tlaboratorio','aten_tnuclear','aten_tfarmacos','aten_timagenes','aten_tsintomas',
            'aten_tindicaciones','aten_tlabo_otros','aten_tima_otros','aten_tnuclear_otros'
            ,'aten_totros','personas.pers_tinfo',
            DB::raw("YEAR(CURDATE())-YEAR(personas.pers_fnacimiento) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(personas.pers_fnacimiento,'%m-%d'), 0 , -1 ) AS edad"),DB::raw("DATE_FORMAT(agen_finicio, '%d/%m/%Y') as agen_finicio"),'personas.pers_tdireccion','atenciones.pers_nrut_medico','m.pers_tfirma','personas.pers_ntipo_docto')
            ->get();
			
        $diagnosticos = array();
        if(isset($datos_atencion[0]->aten_tdiagnostico) && $datos_atencion[0]->aten_tdiagnostico !=""){
        $diagnosticos = Diagnosticos::whereRaw("diag_ncod in (".$datos_atencion[0]->aten_tdiagnostico.")")
            ->get();
        }

        $laboratorios = array();
//        if(isset($datos_atencion[0]->aten_tlaboratorio) && strpos($datos_atencion[0]->aten_tlaboratorio, '<') === false && $datos_atencion[0]->aten_tlaboratorio != ""){
        if(isset($datos_atencion[0]->aten_tlaboratorio) && $datos_atencion[0]->aten_tlaboratorio != ""){
            $laboratorios = Laboratorios::whereRaw("labo_ncod in (".$datos_atencion[0]->aten_tlaboratorio.")")
                ->get();
        }
        
        $nucleares = array();
        if(isset($datos_atencion[0]->aten_tnuclear) && strpos($datos_atencion[0]->aten_tnuclear, '<') === false && $datos_atencion[0]->aten_tnuclear != ""){
            $nucleares = Nucleares::whereRaw("nucl_ncod in (".$datos_atencion[0]->aten_tnuclear.")")
                ->get();
        }

        $imagenes = array();
        if(isset($datos_atencion[0]->aten_timagenes) && strpos($datos_atencion[0]->aten_timagenes, '<') === false && $datos_atencion[0]->aten_timagenes != ""){
            $imagenes = Imagenologias::whereRaw("iman_ncod in (".$datos_atencion[0]->aten_timagenes.")")
                ->get();
        }

        $documentos = AtencionesDocumentos::where('aten_ncod','=',$id)
                    ->select('atdo_ncod','aten_ncod','atdo_tdocumento','atdo_fdocumento',DB::raw("SUBSTRING_INDEX(atdo_tdocumento,'_', -1) as nombre"))
                    ->get();

        $data = [
            'centro_medico' => $centro_medico,
            'datos_atencion' => $datos_atencion,
            'diagnosticos' => $diagnosticos ,
            'laboratorios' => $laboratorios,
            'nucleares' => $nucleares,
            'imagenes' => $imagenes 
        ]; 
        
        
        $tamanio = array(0,0,368.504,595.276);
        $pdf = PDF::loadView('atenciones.receta', $data)->setPaper($tamanio);

        //return $pdf->download('receta_'.$fechaActual.'_'.$id.'.pdf');



         $tMensaje = "<h3>Estimado/a ".$datos_atencion[0]->nombres."</h3><p>Adjunto encontrará la receta de la atención del día ".$datos_atencion[0]->agen_finicio."</p><br/>Saludos<br/>".$centro_medico[0]->ceme_tnombre."<br/> ".$centro_medico[0]->ceme_tdireccion."<p><strong>NOTA:</strong>Este mensaje fue generado por un sistema de correo automático, por lo tanto, no debe responderlo.<br/>Este mensaje y/o documentos adjuntos son confidenciales y están destinados a la(s) persona(s) a la que han sido enviados. Pueden contener información privada y confidencial, cuya difusión se encuentre legalmente prohibida. Si usted no es el destinatario, por favor notifique de inmediato al remitente y elimine el mensaje de sus carpetas y/o archivos.</p>";

         $tAsunto = "Receta médica";

         $data["email"] = $datos_atencion[0]->pers_tcorreo;
         $data["title"] = $tAsunto;
         $data["body"] = $tMensaje;
         $data["fecha"] = $fechaActual;
         $data["id"] = $id;

		//dd($data);

         Mail::send('atenciones.enviarReceta', $data, function($message)use($data, $pdf) {
             $message->to($data["email"], $data["email"])
                     ->subject($data["title"])
                     ->attachData($pdf->output(), 'receta_'.$data["fecha"].'_'.$data["id"].'.pdf');
         });

    	 return Redirect::back()->with('message','Receta enviada');
        //return $pdf->download('receta_'.$fechaActual.'_'.$id.'.pdf');

    }


    function clean($string) {
       $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

       return preg_replace('/[^A-Za-z0-9\_\-]/', '', $string); // Removes special chars.
    }

}
