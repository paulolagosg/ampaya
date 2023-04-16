<?php

namespace App\Http\Controllers;

use App\Models\Agendas;
use App\Models\AgendasBloqueos;
use App\Models\CentrosMedicos;
use App\Models\EstadosAgendas;
use App\Models\Personas;
use App\Models\TiposAtenciones;
use App\Models\TiposPacientes;
use App\Models\Generos;
use App\Models\Previsiones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

use DateTime;
use Mail;


class AgendasController extends Controller
{
    public function lista(){

        $nCentroMedico = Auth::user()->ceme_ncod;
        $nTipoUsuario = Auth::user()->tius_ncod;
        $nIDUsuario = Auth::user()->id;


        if($nTipoUsuario != 2){
            $medicos = Personas::join('users','personas.user_id','=','users.id')
                    ->where('users.tius_ncod','=',2)
                    ->where('users.ceme_ncod','=',$nCentroMedico)
                    ->where('personas.pers_nestado','=',1)
                    ->select(DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno) as nombre"), 'personas.pers_nrut','users.user_nminutos')
                    ->get();
        }
        else{
            $medicos = Personas::join('users','personas.user_id','=','users.id')
                    ->where('users.tius_ncod','=',2)
                    ->where('users.ceme_ncod','=',$nCentroMedico)
                    ->where('personas.pers_nestado','=',1)
                    ->where('users.id','=',$nIDUsuario)
                    ->select(DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno) as nombre"), 'personas.pers_nrut','users.user_nminutos')
                    ->get();
        }


        return view('agenda.agenda',compact('medicos','nTipoUsuario'));
    }

    public function datosAgenda($nMedico){
		
		$nDatos = explode('-',$nMedico);
        $nCentroMedico = Auth::user()->ceme_ncod;


        $bloqueos = AgendasBloqueos::where('pers_nrut_medico','=',$nDatos[0])
                    ->where('agbl_nestado','=',1)
                    ->where('ceme_ncod','=',$nCentroMedico)
                    ->select(DB::raw("'Agenda' as pers_tnombres"),DB::raw("'Bloqueada' as pers_tpaterno"),'agbl_finicio','agbl_ftermino',DB::raw("0 as agen_ncod"),DB::raw("'#ff0000' as color"));


        $datos = Agendas::join('personas','agendas.pers_nrut_paciente','=','personas.pers_nrut')
                        ->where('pers_nrut_medico', '=', $nDatos[0])
                        ->where('pers_nestado','=',1)
                        ->where('personas.ceme_ncod','=',$nCentroMedico)
                        ->select('personas.pers_tnombres','personas.pers_tpaterno','agendas.agen_finicio','agendas.agen_ftermino','agendas.agen_ncod',DB::raw("case esag_ncod when 1 then '#ea9a04' when 2 then '#2bae07' when 3 then '#0737ae' when 4 then '#ff0000' when 5 then '#f9f911' end as color"))
            ->union($bloqueos)
            ->get();


        $tSalida = '[';
        foreach($datos as $dato){
            if($tSalida == "["){
                $tSalida .= '{
                    "title":"'.$dato->pers_tnombres.' '.$dato->pers_tpaterno.'",
                    "start":"'.$dato->agen_finicio.'",
                    "end":"'.$dato->agen_ftermino.'",
                    "url":"/agenda/editar/'.$dato->agen_ncod.'",
                    "color": "'.$dato->color.'"
                }';
            }
            else{
                $tSalida .= ',{
                    "title":"'.$dato->pers_tnombres.' '.$dato->pers_tpaterno.'",
                    "start":"'.$dato->agen_finicio.'",
                    "end":"'.$dato->agen_ftermino.'",
                    "url":"/agenda/editar/'.$dato->agen_ncod.'",
                    "color": "'.$dato->color.'"
                }';

            }
        }
        $tSalida .=']';

        return $tSalida;
    }

    public function crear(){

        $nCentroMedico = Auth::user()->ceme_ncod;

        $estados = EstadosAgendas::where('esag_nestado','=',1)
                    ->orderBy('esag_tnombre')
                    ->get();
        $atenciones = TiposAtenciones::where('tiat_nestado','=',1)
                    ->orderBy('tiat_tnombre')
                    ->get();
        $centros = CentrosMedicos::where('ceme_nestado','=',1)
                    ->orderBy('ceme_tnombre')
                    ->get();
        $medicos = Personas::join('users','personas.user_id','=','users.id')
                    ->where('users.tius_ncod','=',2)
                    ->where('users.ceme_ncod','=',$nCentroMedico)
                    ->where('personas.pers_nestado','=',1)
                    ->select(DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno) as nombre"), 'personas.pers_nrut')
                    ->get();
        $pacientes = Personas::where('pers_bpaciente','=',1)
                    ->where('ceme_ncod','=',$nCentroMedico)
                    ->where('personas.pers_nestado','=',1)
                    ->select(DB::raw("concat(pers_tnombres,' ',pers_tpaterno) as nombre"), 'pers_nrut')
                    ->get();
        $tipos_pacientes = TiposPacientes::where('tipa_nestado','=',1)
                    ->select('tipa_ncod','tipa_tnombre')
                    ->get();


        return view('agenda.crear',compact('estados','atenciones','medicos','pacientes','tipos_pacientes'));
    }

    public function agregar(Request $request){

        $nSobrecupo = 0;
        $nCentroMedico = Auth::user()->ceme_ncod;

        if(isset($request->agen_nsobrecupo)){
            $nSobrecupo = $request->agen_nsobrecupo;
        }

        $this->validate($request,[
                'pers_nrut_medico' => 'required',
                'agen_finicio' => 'required|date',
                'agen_hinicio' => 'required|date_format:H:i',
                'pers_nrut_paciente' => 'required',
                'tiat_ncod' => 'required',
                'tipa_ncod' => 'required'
            ]);
        $fechaConsulta = $request->agen_finicio." ".$request->agen_hinicio;

        $sql = "select count(1) from agendas where agen_finicio = '".$fechaConsulta."'";
        
        //echo $sql;
        //DB::enableQueryLog(); 
        $nExisteHora = 0;
        if($nSobrecupo == 0){
            $bloqueosAgendas = AgendasBloqueos::where('pers_nrut_medico','=',$request->pers_nrut_medico)
                    ->where('agbl_nestado','=',1)
                    ->where('ceme_ncod','=',$nCentroMedico)
                     ->whereRaw("'".$fechaConsulta."' between agbl_finicio and agbl_ftermino")
                    ->select('agbl_ncod');

            $nExisteHora = Agendas::where('agen_finicio','=',$fechaConsulta)
                        ->where('ceme_ncod','=',$nCentroMedico)
                        ->select('agen_ncod')
                        ->union($bloqueosAgendas)
                        ->count();
        }

        //dd(DB::getQueryLog());
        if(intval($nExisteHora) > 0){
            return Redirect::back()->withInput($request->input())->withErrors(['Hora'=>'La hora no está disponible']);
        }

        $sqlInsert = "insert into agendas (agen_finicio,agen_ftermino,agen_nsobrecupo,pers_nrut_paciente,pers_nrut_medico,tiat_ncod,ceme_ncod,esag_ncod) values ('".$fechaConsulta."','".$fechaConsulta."',".$nSobrecupo.",".$request->pers_nrut_paciente.",".$request->pers_nrut_medico.",".$request->tiat_ncod.",".$nCentroMedico.",1)";
        
        //echo $sqlInsert;exit;

        $agenda = new Agendas();
        $agenda->agen_finicio = $fechaConsulta;
        $agenda->agen_ftermino = $fechaConsulta;
        $agenda->agen_nsobrecupo = $nSobrecupo;
        $agenda->pers_nrut_paciente = $request->pers_nrut_paciente;
        $agenda->pers_nrut_medico = $request->pers_nrut_medico;
        $agenda->tiat_ncod = $request->tiat_ncod;
        $agenda->tipa_ncod = $request->tipa_ncod;
        $agenda->ceme_ncod = $nCentroMedico;
        $agenda->esag_ncod = 1;
        $agenda->save();
        

        $sqlPersona = "update personas set pers_tnotas = '".$request->pers_tnota."' where pers_nrut = ".$request->pers_nrut_paciente." ";

        DB::statement($sqlPersona);
        //DB::enableQueryLog(); // Enable query log
       
             //dd(DB::getQueryLog()); // Show results of log

        return redirect()->route('agenda')->with('message','Hora registrada!');

    }


    public function editar($id){

        try{
            $datos = Agendas::where('agen_ncod', '=', $id)->firstOrFail();
        }
        catch(ModelNotFoundException $e){
            abort(404, __('Sorry, the page you are looking for is not available.'));    
        }


        $nCentroMedico = Auth::user()->ceme_ncod;

        $estados = EstadosAgendas::where('esag_nestado','=',1)
                    ->orderBy('esag_tnombre')
                    ->get();
        $atenciones = TiposAtenciones::where('tiat_nestado','=',1)
                    ->orderBy('tiat_tnombre')
                    ->get();
        $centros = CentrosMedicos::where('ceme_nestado','=',1)
                    ->orderBy('ceme_tnombre')
                    ->get();
        $medicos = Personas::join('users','personas.user_id','=','users.id')
                    ->where('users.tius_ncod','=',2)
                    ->where('users.ceme_ncod','=',$nCentroMedico)
                    ->where('personas.pers_nestado','=',1)
                    ->select(DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno) as nombre"), 'personas.pers_nrut')
                    ->get();
        $pacientes = Personas::where('pers_bpaciente','=',1)
                    ->where('ceme_ncod','=',$nCentroMedico)
                    ->where('personas.pers_nestado','=',1)
                    ->select(DB::raw("concat(pers_tnombres,' ',pers_tpaterno) as nombre"), 'pers_nrut')
                    ->get();
        $agenda = Agendas::where('agen_ncod','=',$id)
                    ->join('personas','agendas.pers_nrut_paciente','=','personas.pers_nrut')
                    ->where('personas.ceme_ncod',$nCentroMedico)
                    ->select(DB::raw("DATE_FORMAT(agen_finicio, '%Y-%m-%d') as agen_finicio"), DB::raw("DATE_FORMAT(agen_finicio, '%H:%i') agen_hinicio"),'pers_nrut_medico','pers_nrut_paciente','esag_ncod','tiat_ncod','agen_nsobrecupo','agen_ncod','tipa_ncod','personas.pers_tnotas')
                    ->get();
        $tipos_pacientes = TiposPacientes::where('tipa_nestado','=',1)
                    ->select('tipa_ncod','tipa_tnombre')
                    ->get();
        
        $horas_disponibles =[];

        return view('agenda.editar',compact('estados','atenciones','medicos','pacientes','agenda','tipos_pacientes'));
    }

    public function modificar(Request $request){

        $nSobrecupo = 0;
        $nCentroMedico = Auth::user()->ceme_ncod;

        if(isset($request->agen_nsobrecupo)){
            $nSobrecupo = $request->agen_nsobrecupo;
        }

        $this->validate($request,[
                'pers_nrut_medico' => 'required',
                'agen_finicio' => 'required|date',
                'agen_hinicio' => 'required|date_format:H:i',
                'pers_nrut_paciente' => 'required',
                'tiat_ncod' => 'required',
                'tipa_ncod' => 'required'
            ]);
        $fechaConsulta = $request->agen_finicio." ".$request->agen_hinicio;

        $sql = "select count(1) from agendas where agen_finicio = '".$fechaConsulta."' and agen_ncod <> ".$request->agen_ncod;
        
        $nExisteHora = 0;

        if($nSobrecupo == 0){

            $bloqueos = AgendasBloqueos::where('pers_nrut_medico','=',$request->pers_nrut_medico)
                    ->where('agbl_nestado','=',1)
                    ->whereRaw("'".$fechaConsulta."' between agbl_finicio and agbl_ftermino")
                    ->select('agbl_ncod');

            $nExisteHora = Agendas::where('agen_finicio','=',$fechaConsulta)
                            ->where('agen_ncod','<>',$request->agen_ncod)
                            ->select('agen_ncod')
                            ->union($bloqueos)
                            ->count();
        }

        if($nExisteHora > 0){
            return Redirect::back()->withInput($request->input())->withErrors(['Hora'=>'La hora no está disponible']);
        }

        $sqlUpdate = "update agendas set agen_finicio = '".$fechaConsulta."', agen_ftermino = DATE_ADD('".$fechaConsulta."', INTERVAL 30 MINUTE), pers_nrut_medico=".$request->pers_nrut_medico.", pers_nrut_paciente = ".$request->pers_nrut_paciente.",agen_nsobrecupo= ".$nSobrecupo.", tiat_ncod=".$request->tiat_ncod.", esag_ncod=".$request->esag_ncod.", tipa_ncod = ".$request->tipa_ncod." where agen_ncod = ".$request->agen_ncod." ";

        $sqlPersona = "update personas set pers_tnotas = '".$request->pers_tnota."' where pers_nrut = ".$request->pers_nrut_paciente." ";
        
        //echo $sqlPersona;exit;

        DB::beginTransaction();

        try {
            DB::statement($sqlUpdate);
            DB::statement($sqlPersona);
            DB::commit();
            return redirect()->route('agenda')->with('message','Registro modificado!');
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->withInput($request->input())->withErrors(['Error'=>'Ocurrió un error al intentar modificar, favor intente nuevamente']);
        }


    }

    public function bloqueos(){

        $nCentroMedico = Auth::user()->ceme_ncod;


        $medicos = Personas::join('users','personas.user_id','=','users.id')
                    ->where('users.tius_ncod','=',2)
                    ->where('users.ceme_ncod','=',$nCentroMedico)
                    ->where('personas.pers_nestado','=',1)
                    ->select(DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno) as nombre"), 'personas.pers_nrut')
                    ->get();

        return view('agenda.bloquear',compact('medicos'));
    }


    public function bloquear(Request $request){
        $nCentroMedico = Auth::user()->ceme_ncod;
        //dd($request);
        $this->validate($request,[
                'pers_nrut_medico' => 'required',
                'agbl_finicio' => 'required|date',
                'agbl_ftermino' => 'required|date'
            ],

            [ 'agbl_finicio.required' => 'El campo Fecha de inicio no puede ser vacío.',
             'agbl_ftermino.required' => 'El campo Fecha de término no puede ser vacío.']);

        $agendaB = new AgendasBloqueos();
        $agendaB->agbl_finicio = $request->agbl_finicio;
        $agendaB->agbl_ftermino = $request->agbl_ftermino;
        $agendaB->pers_nrut_medico = $request->pers_nrut_medico;
        $agendaB->ceme_ncod = $nCentroMedico;
        $agendaB->agbl_nestado= 1;
        $agendaB->save();



        return Redirect::back()->with('message','Registro modificado!');
    }



    public function listaBloqueos(){

        $nCentroMedico = Auth::user()->ceme_ncod;

        $bloqueos = AgendasBloqueos::join('personas','agendas_bloqueos.pers_nrut_medico','=','personas.pers_nrut')
            ->where('agendas_bloqueos.ceme_ncod','=',$nCentroMedico)
            ->select(DB::raw("concat(pers_tnombres,' ',pers_tpaterno) as medico"),DB::raw("DATE_FORMAT(agbl_finicio, '%d/%m/%Y') agbl_finicio"),DB::raw("DATE_FORMAT(agbl_ftermino, '%d/%m/%Y') agbl_ftermino"),DB::raw("case agbl_nestado when 0 then 'No vigente' else 'Vigente' end as estado"),'agbl_nestado','agbl_ncod')

            ->get();

        return view('bloqueos.lista',compact('bloqueos'));
    }

    public function eliminar($id){
        try{
            $datos = AgendasBloqueos::where('agbl_ncod', '=', $id)->firstOrFail();
        }
        catch(ModelNotFoundException $e){
            abort(404, __('Sorry, the page you are looking for is not available.'));    
        }

        $sqlUpdate = "update agendas_bloqueos set agbl_nestado = 0 where agbl_ncod = ".$id." ";
        
        //echo $sqlUpdate;exit;

        DB::beginTransaction();

        try {
            DB::statement($sqlUpdate);

            DB::commit();
            return redirect()->route('bloqueos.lista')->with('message','Registro modificado!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('bloqueos.lista')->withErrors(['Error'=>'Ocurrió un error al intentar modificar, favor intente nuevamente']);
        }
    }


    public function tabla(){
        $nCentroMedico = Auth::user()->ceme_ncod;
        $nTipoUsuario = Auth::user()->tius_ncod;
        $nIDUsuario = Auth::user()->id;

		DB::enableQueryLog();

        if($nTipoUsuario != 2){
            $agenda = Agendas::join('personas','agendas.pers_nrut_paciente','=','personas.pers_nrut')
                ->join('personas as p2','p2.pers_nrut','=','agendas.pers_nrut_medico')
                ->join('users','users.id','=','p2.user_id')
                ->join('estados_agendas','agendas.esag_ncod','=','estados_agendas.esag_ncod')
                ->join('tipos_pacientes','tipos_pacientes.tipa_ncod','=','agendas.tipa_ncod')
                ->where('agendas.ceme_ncod','=',$nCentroMedico)
                ->where('p2.pers_nestado',1)
                ->where('users.ceme_ncod',$nCentroMedico)
                ->where('personas.ceme_ncod',$nCentroMedico)
                ->whereRaw("date_format(agendas.agen_finicio,'%d/%m/%Y') = date_format(now(),'%d/%m/%Y')")
                ->orderBy('agen_finicio','DESC')
                ->select(DB::raw("concat(p2.pers_tnombres,' ',p2.pers_tpaterno) as medico"),DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno) as paciente"),DB::raw("date_format(agendas.agen_finicio,'%d/%m/%Y %H:%i') as agen_finicio"),DB::raw("date_format(agendas.agen_finicio,'%d/%m/%Y') as orden"),DB::raw("date_format(agendas.agen_ftermino,'%d/%m%Y') as agen_ftermino"),'agendas.agen_ncod',DB::raw("case agendas.esag_ncod when 1 then '#ea9a04' when 2 then '#2bae07' when 3 then '#0737ae' when 4 then '#ff0000' when 5 then '#f9f911' end as color"),DB::raw("case agendas.esag_ncod when 1 then '#ea9a04' when 2 then '#2bae07' when 3 then '#0737ae' when 4 then '#ff0000' when 5 then '#f9f911' end as color"),'esag_tnombre','agendas.esag_ncod','tipa_tnombre',DB::raw("case when personas.pers_ntipo_docto = 1 then concat(personas.pers_nrut,'-',personas.pers_tdv) else personas.pers_nrut end as rut"),'personas.pers_tnotas')
                ->get();
        }
        else{
            $agenda = Agendas::join('personas','agendas.pers_nrut_paciente','=','personas.pers_nrut')
                ->join('personas as p2','p2.pers_nrut','=','agendas.pers_nrut_medico')
                ->join('users','users.id','=','p2.user_id')
                ->join('estados_agendas','agendas.esag_ncod','=','estados_agendas.esag_ncod')
                ->join('tipos_pacientes','tipos_pacientes.tipa_ncod','=','agendas.tipa_ncod')
                ->leftJoin('atenciones','agendas.agen_ncod','atenciones.agen_ncod')
                ->where('agendas.ceme_ncod','=',$nCentroMedico)
                ->where('users.id','=',$nIDUsuario)
                ->where('personas.ceme_ncod',$nCentroMedico)                
                ->whereRaw("date_format(agendas.agen_finicio,'%d/%m/%Y') = date_format(now(),'%d/%m/%Y')")
                ->orderBy('agen_finicio','ASC')
                ->select(DB::raw("coalesce(atenciones.aten_ncod) as aten_ncod"),DB::raw("concat(p2.pers_tnombres,' ',p2.pers_tpaterno) as medico"),DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno) as paciente"),DB::raw("date_format(agendas.agen_finicio,'%d/%m/%Y %H:%i') as agen_finicio"),DB::raw("date_format(agendas.agen_ftermino,'%d/%m%Y') as agen_ftermino"),'agendas.agen_ncod',DB::raw("case agendas.esag_ncod when 1 then '#ea9a04' when 2 then '#2bae07' when 3 then '#0737ae' when 4 then '#ff0000' when 5 then '#f9f911' end as color"),DB::raw("case agendas.esag_ncod when 1 then '#ea9a04' when 2 then '#2bae07' when 3 then '#0737ae' when 4 then '#ff0000' when 5 then '#f9f911' end as color"),'esag_tnombre','agendas.esag_ncod','tipa_tnombre',DB::raw("case when personas.pers_ntipo_docto = 1 then concat(personas.pers_nrut,'-',personas.pers_tdv) else personas.pers_nrut end as rut"),'personas.pers_tnotas')
                ->get();
        }
        
        //dd(DB::getQueryLog());

        $estados = EstadosAgendas::where('esag_nestado','=',1)
                    ->orderBy('esag_tnombre')
                    ->get();

        return view('dashboard',compact('agenda','estados','nTipoUsuario')); 


    }

    public function cambiaEstado($estado,$id){

        $sqlUpdate = "update agendas set esag_ncod = ".$estado." where agen_ncod = ".$id." ";
        
        //echo $sqlUpdate;exit;

        DB::beginTransaction();

        try {
            DB::statement($sqlUpdate);

            DB::commit();
            return redirect()->route('dashboard')->with('message','Registro modificado!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('dashboard')->withErrors(['Error'=>'Ocurrió un error al intentar modificar, favor intente nuevamente']);
        }
    }

	public function getHoras($id,$nFormato,$nSeleccionada = '5:00'){
		$intervalo = User::join('personas','personas.user_id','users.id')
			->where('personas.pers_nrut',$id)
			->first();
		
		//formato -> 1:listbox - 2: arreglo

		$tSalida = "";
		
		$hora_inicio = $intervalo->user_nhora_inicio.':00';
		$hora_fin = $intervalo->user_nhora_fin.':00';
    	$hora_inicio = new DateTime( $hora_inicio );
   		$hora_fin    = new DateTime( $hora_fin );
    	$hora_fin->modify('+1 second'); // Añadimos 1 segundo para que nos muestre $hora_fin

    // Si la hora de inicio es superior a la hora fin
    // añadimos un día más a la hora fin
    	if ($hora_inicio > $hora_fin) {

        	$hora_fin->modify('+1 day');
    	}
		
    // Establecemos el intervalo en minutos        
    	$intervaloMedico = new \DateInterval('PT'.$intervalo->user_nminutos.'M');
    // Sacamos los periodos entre las horas
    	$periodo   = new \DatePeriod($hora_inicio, $intervaloMedico, $hora_fin);        

    	foreach( $periodo as $hora ) {

        // Guardamos las horas intervalos 
        	$horas[] =  $hora->format('H:i');
    	}

		

		switch($nFormato){
			case 1:
				foreach($horas as $dato){
					if($dato == $nSeleccionada){
						$tSalida .= "<option value='".$dato."' selected>".$dato."</option>";
					}
					else{
						$tSalida .= "<option value='".$dato."'>".$dato."</option>";
					}
				}
				return $tSalida;
				break;
			case 2:
				return $horas;
				break;
			default:
				return $horas;
				break;
		}	
	}

	public function dias_bloqueados($id){
		$arregloDias = [];
		try{
            $datos = User::join('personas','users.id','personas.user_id')
            		->where('personas.pers_nrut', $id)->firstOrFail();
        }
        catch(ModelNotFoundException $e){
            return $arregloDias;    
        }
		
		$datos = User::join('personas','users.id','personas.user_id')
            		->where('personas.pers_nrut', $id)->first();
		
		$nDias = explode(',',$datos->user_tdias);
		
		foreach($nDias as $d){
			$arregloDias[] = intval($d);
		}
		
		return $datos->user_tdias;
	}
	
	public function solicitar($slug){
	
		try{
            $datos = CentrosMedicos::where('ceme_tslug', '=', $slug)->firstOrFail();
        }
        catch(ModelNotFoundException $e){
            abort(404, __('Sorry, the page you are looking for is not available.'));    
        }

		$datos = CentrosMedicos::where('ceme_tslug', '=', $slug)->first();
//		dd($datos);
		$medicos = Personas::join('users','personas.user_id','=','users.id')
					->join('especialidades as e','e.espe_ncod','personas.espe_ncod')
                    ->where('users.tius_ncod','=',2)
                    ->where('users.ceme_ncod','=',$datos->ceme_ncod)
                    ->where('personas.pers_nestado','=',1)
                    ->select(DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno) as nombre"), 'personas.pers_nrut','users.user_nminutos',
                    'user_tfoto','personas.pers_tinfo','users.id','user_tdias','gene_ncod','espe_tnombre','users.ceme_ncod')
                    ->get();

        $generos = Generos::orderBy('gene_tnombre')->where('gene_nestado','=',1)->get();
        $previsiones = Previsiones::orderBy('prev_tnombre')->where('prev_nestado','=',1)->get();
                    
		return view('solicitar',compact('medicos','generos','previsiones')); 
	}
	
	public function muestra_horas($id,$fecha){
	
		date_default_timezone_set('UTC');

		$tz = 'America/Santiago';
		$timestamp = time();
	
		$intervalo = User::join('personas','personas.user_id','users.id')
			->where('personas.pers_nrut',$id)
			->first();
		
		$fecha_formato = explode('-',$fecha);
		$fecha_formato = $fecha_formato[2].'/'.$fecha_formato[1].'/'.$fecha_formato[0];    	
    	$horas = [];
    	
    	$hora_inicio = $intervalo->user_nhora_inicio;
		$hora_fin = $intervalo->user_nhora_fin;
    	$hora_inicio = new DateTime( $hora_inicio , new \DateTimeZone($tz));
    	$hora_fin = new DateTime( $hora_fin , new \DateTimeZone($tz));
    	$hora_fin->modify('+1 second'); 

		if ($hora_inicio > $hora_fin) {
        	$hora_fin->modify('+1 day');
    	}
    	

    	$hora_actual = new DateTime(date('H:i'), new \DateTimeZone($tz));
    	$hora_actual->setTimestamp($timestamp); 
		//print_r($hora_inicio);
		//print_r($hora_actual);
		
		
		
		//exit;
		

		DB::enableQueryLog(); 
 

		$bloqueos = AgendasBloqueos::where('pers_nrut_medico','=',$intervalo->pers_nrut)
                    ->where('agbl_nestado','=',1)
                    ->where('ceme_ncod','=',$intervalo->ceme_ncod)
					->whereRaw("date_format(agbl_finicio,'%Y-%m-%d') = date_format('".$fecha."','%Y-%m-%d')")                    
                    ->select(DB::raw("date_format(agbl_finicio,'%H:%i') as hora"),DB::raw("'Agenda' as pers_tnombres"),DB::raw("'Bloqueada' as pers_tpaterno"),'agbl_finicio','agbl_ftermino',DB::raw("0 as agen_ncod"),DB::raw("'#ff0000' as color"));
		

        $datos = Agendas::join('personas','agendas.pers_nrut_paciente','=','personas.pers_nrut')
                        ->where('pers_nrut_medico', '=', $intervalo->pers_nrut)
                        ->where('pers_nestado','=',1)
                        ->where('personas.ceme_ncod','=',$intervalo->ceme_ncod)
						->whereRaw("date_format(agendas.agen_finicio,'%Y-%m-%d') = date_format('".$fecha."','%Y-%m-%d')")                    
                        ->select(DB::raw("date_format(agendas.agen_finicio,'%H:%i') as hora"),'personas.pers_tnombres','personas.pers_tpaterno','agendas.agen_finicio','agendas.agen_ftermino','agendas.agen_ncod',DB::raw("case esag_ncod when 1 then '#ea9a04' when 2 then '#2bae07' when 3 then '#0737ae' when 4 then '#ff0000' when 5 then '#f9f911' end as color"))
            ->union($bloqueos)
            ->get();
        //
        //exit;
        //dd(DB::getQueryLog());
		
		$horasTomadas = [];
		foreach($datos as $ht){
			$horasTomadas[] = $ht->hora;
		}		

    	$intervaloMedico = new \DateInterval('PT'.$intervalo->user_nminutos.'M');

    	$periodo   = new \DatePeriod($hora_inicio, $intervaloMedico, $hora_fin);        


    	foreach( $periodo as $hora ) {
    		if($hora->format('Y-m-d') == $fecha){ //para el día actual se muestran solo las horas mayores a la actual
    			if($hora->format('H:i') > $hora_actual->format('H:i')){
	        		$horas[] =  $hora->format('H:i');
	        	}
	        }
	        else{
	        	$horas[] =  $hora->format('H:i');
	        }
    	}

    	$mostrar = array_diff($horas,$horasTomadas);
    	
    	$horas = array_diff($horas,$horasTomadas);
    	
		return view('horas',compact('fecha','horas','fecha_formato')); 
	
	}
	
	public function reservar(Request $request){
		//dd($request);
		$nTipoDocto = $request->pers_ntipo_docto;

		if($nTipoDocto == 1){
            $this->validate($request,[
                //'rut' => 'required|min:5|valida_rut',
                'pers_nrut_paciente' => 'required|min:5',
                'pers_tnombres' => 'required',
                'pers_tpaterno' => 'required',
                'pers_tnombres' => 'required',
                'pers_fnacimiento' => 'required|date_format:d/m/Y',
                //'pers_tcorreo' => 'required|email|unique:personas',
                'pers_tcorreo' => 'required|email',
                'pers_tdireccion' => 'required',
                //'gene_ncod' => 'required',
                'prev_ncod' => 'required',
            ]);
            $explode = explode("-",$request->pers_nrut_paciente);
            $pers_nrut = $explode[0];
            $pers_tdv = $explode[1];
        }
        else{
            $this->validate($request,[
                'pers_nrut_paciente' => 'required|min:5',
                'pers_tnombres' => 'required',
                'pers_tpaterno' => 'required',
                'pers_tnombres' => 'required',
                'pers_fnacimiento' => 'required|date_format:d/m/Y',
                //'pers_tcorreo' => 'required|email|unique:personas',
                'pers_tcorreo' => 'required|email',
                'pers_tdireccion' => 'required',
                //'gene_ncod' => 'required',
                'prev_ncod' => 'required',
            ]);
            $pers_nrut = $request->pers_nrut_paciente;
            $pers_tdv = "";
        }

		$nExisteRut = Personas::where('pers_nrut','=',$pers_nrut)
					->where('ceme_ncod',$request->ceme_ncod)
					->count();
    
        
        $fNacimiento = explode('/',$request->pers_fnacimiento);
        $fNacimiento = $fNacimiento[2]."-".$fNacimiento[1]."-".$fNacimiento[0];
        
        $fConsulta = explode('/',$request->fseleccionada);
        $fConsulta = $fConsulta[2]."-".$fConsulta[1]."-".$fConsulta[0];
        
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

//        $sqlUpdate = "update personas set pers_tnombres = '".$request->pers_tnombres."',pers_tpaterno= '".$request->pers_tpaterno."' ,pers_tmaterno = '".$request->pers_tmaterno."' ".$updateFijo.$updateMovil." , pers_fnacimiento = '".$fNacimiento."', pers_tcorreo = '".$request->pers_tcorreo."', pers_tdireccion = '".$request->pers_tdireccion."', prev_ncod = ".$request->prev_ncod.", gene_ncod = ".$request->gene_ncod.", pers_ntipo_docto = $nTipoDocto where pers_nrut = ".$pers_nrut." and ceme_ncod = ".$request->ceme_ncod;
        $sqlUpdate = "update personas set pers_tnombres = '".$request->pers_tnombres."',pers_tpaterno= '".$request->pers_tpaterno."' ,pers_tmaterno = '".$request->pers_tmaterno."' ".$updateFijo.$updateMovil." , pers_fnacimiento = '".$fNacimiento."', pers_tcorreo = '".$request->pers_tcorreo."', pers_tdireccion = '".$request->pers_tdireccion."', prev_ncod = ".$request->prev_ncod.", pers_ntipo_docto = $nTipoDocto where pers_nrut = ".$pers_nrut." and ceme_ncod = ".$request->ceme_ncod;
        
        $medico = Personas::join('especialidades as e','e.espe_ncod','personas.espe_ncod')
        			->join('centros_medicos as c','personas.ceme_ncod','c.ceme_ncod')
        			->join('users as u','personas.user_id','u.id')
        			->where('pers_nrut',$request->pers_nrut_medico)
        			->where('c.ceme_ncod',$request->ceme_ncod)
        			->first();
        $fechaActual = date('Ymd_His');
        
        DB::beginTransaction();

        try {
            if(intval($nExisteRut == 0)){
            	$nTipoPaciente = 1;
            
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
		        $paciente->pers_fnacimiento = $fNacimiento;
		        $paciente->pers_tcorreo = $request->pers_tcorreo;
		        $paciente->pers_tdireccion = $request->pers_tdireccion;
        		$paciente->pers_bpaciente = 1;
		        $paciente->prev_ncod = $request->prev_ncod;
       			//$paciente->gene_ncod = $request->gene_ncod;
        		$paciente->espe_ncod = 0;
		        $paciente->ceme_ncod = $request->ceme_ncod;
		        $paciente->pers_nestado = 1;
        		$paciente->pers_ntipo_docto = $nTipoDocto;
		        $paciente->save();
            }
            else{
            	$nTipoPaciente = 2;
            	DB::statement($sqlUpdate);
            }
            
            $fechaConsulta = $fConsulta." ".$request->hora;
            
            $agenda = new Agendas();
        	$agenda->agen_finicio = $fechaConsulta;
        	$agenda->agen_ftermino = $fechaConsulta;
        	$agenda->agen_nsobrecupo = 0;
        	$agenda->pers_nrut_paciente = $pers_nrut;
        	$agenda->pers_nrut_medico = $request->pers_nrut_medico;
        	$agenda->tiat_ncod = 1;
        	$agenda->tipa_ncod = $nTipoPaciente;
        	$agenda->ceme_ncod = $request->ceme_ncod;
        	$agenda->esag_ncod = 1;
        	$agenda->save();
        	
        	$idReserva = $agenda->agen_ncod;
             
            
            DB::commit();
			$tMensaje = "<img src='".url("/")."/img/".$medico->ceme_tlogo."' style='width:30%' />";
			$tMensaje .= "<h3>Hola ".$request->pers_tnombres." ".$request->pers_tpaterno."</h3>";
         	$tMensaje .= "Su reserva ha sido agendada con éxito.";
         	$tMensaje .= "<table style='width:98%'>";
         	$tMensaje .= "<tr><td>¿Cuándo?</td><td>&nbsp;</td><td><strong>".$request->fseleccionada." a las ".$request->hseleccionada." hrs.</strong></td></tr>";
         	$tMensaje .= "<tr><td>¿Con quién?</td><td>&nbsp;</td><td><strong>".$medico->pers_tnombres." ".$medico->pers_tpaterno." ".$medico->pers_tmaterno."</strong></td></tr>";
         	$tMensaje .= "<tr><td>Especialidad</td><td>&nbsp;</td><td><strong>".$medico->espe_tnombre."</strong></td></tr>";
         	$tMensaje .= "<tr><td>¿Dónde?</td><td>&nbsp;</td><td><strong>".$medico->ceme_tdireccion."</strong></td></tr>";
         	$tMensaje .= "</table><p>&nbsp;</p>";
         	$tMensaje .= "<br/>Saludos<br/>".$medico->ceme_tnombre."<br/> ".$medico->ceme_tdireccion."<p>";
         	if($medico->user_tboton_pago != ""){
         		$tMensaje .="<p>Pague su reserva</p><p>&nbsp;</p>";
         		$tMensaje .="<p><a href='".$medico->user_tboton_pago."' style='border-width: 18px!important;border-color: RGBA(255,255,255,0);border-radius: 32px;letter-spacing: 4px;font-size: 20px;font-weight: 500!important;background-color: #5bbfb5 !important;text-transform: uppercase;color:#fff !important; padding:20px' >Pagar</a></p>";
    		}
         	$tMensaje .= "<p>&nbsp;</p>";
         	$tMensaje .= "<p><strong>IMPORTANTE:</strong> Si su atención es presencial, debe presentarse con 10 minutos de anticipación y con cédula de identidad vigente.<br/>";
         	$tMensaje .= "Solo se permite 1 acompañante para pacientes pediátricos o adultos con necesidad de asistencia.<br/>";
         	$tMensaje .= "Debido a la situación actual, nuestro centro médico puede presentar retrasos en la atención, sin embargo, estamos trabajando para que el servicio brindado sea lo más expedito y cálido posible. Agradecemos tu comprensión.</p>";
         	$tMensaje .= "<p><strong>NOTA:</strong> Este mensaje y/o documentos adjuntos son confidenciales y están destinados a la(s) persona(s) a la que han sido enviados. Pueden contener información privada y confidencial, cuya difusión se encuentre legalmente prohibida. Si usted no es el destinatario, por favor notifique de inmediato al remitente y elimine el mensaje de sus carpetas y/o archivos.</p>";
         	$tMensaje .= "<p><strong>Este mensaje fue generado por un sistema de correo automático, por lo tanto, no debe responderlo.<br/><img src='".url("/")."/img/logo.png' style='width:15%' /></strong></p>";
         	
         	
         	
			//correo al usuario
         	$tAsunto = "Confirmación de reserva";

         	$data["email"] = $request->pers_tcorreo;
         	$data["title"] = $tAsunto;
         	$data["body"] = $tMensaje;
         	$data["fecha"] = $fechaActual;
         	$data["id"] = $idReserva;

			//dd($data);

         	Mail::send('agenda.enviarReserva', $data, function($message)use($data) {
         	    $message->to($data["email"], $data["email"])
         	            ->subject($data["title"]);
            	        //->attachData($pdf->output(), 'receta_'.$data["fecha"].'_'.$data["id"].'.pdf');
	         });


			//correo al médico/centro médico
			$tSolicitante = "";
			if($nTipoDocto == 1){
				$tSolicitante .= "RUT: ".$pers_nrut."-".$pers_tdv;
			}
			else{
				$tSolicitante .= "DNI/Pasaporte: ".$pers_nrut;
			}
			
			$tSolicitante .=  " | Nombre: ".$request->pers_tnombres." ".$request->pers_tpaterno." ".$request->pers_tmaterno." | Teléfono(s) ".$request->pers_nfono_fijo." ".$request->pers_nfono_movil." | Correo Electrónico: ".$request->pers_tcorreo;
			$tMensajeCM = "<img src='".url("/")."/img/".$medico->ceme_tlogo."' style='width:30%' />";
			$tMensajeCM .= "<h3>Nueva reserva realizada</h3>";
         	$tMensajeCM .= "Se ha realizado una nueva reserva vía web.";
         	$tMensajeCM .= "<table style='width:98%'>";
         	$tMensajeCM .= "<tr><td>¿Cuándo?</td><td>&nbsp;</td><td><strong>".$request->fseleccionada." a las ".$request->hseleccionada." hrs.</strong></td></tr>";
         	$tMensajeCM .= "<tr><td>¿Con quién?</td><td>&nbsp;</td><td><strong>".$medico->pers_tnombres." ".$medico->pers_tpaterno." ".$medico->pers_tmaterno."</strong></td></tr>";
         	$tMensajeCM .= "<tr><td>Especialidad</td><td>&nbsp;</td><td><strong>".$medico->espe_tnombre."</strong></td></tr>";
         	$tMensajeCM .= "<tr><td>¿Quién?</td><td>&nbsp;</td><td><strong>".$tSolicitante."</strong></td></tr>";
         	$tMensajeCM .= "</table><p>&nbsp;</p>";
         	$tMensajeCM .= "<br/>Saludos<br/>".$medico->ceme_tnombre."<br/> ".$medico->ceme_tdireccion."<p>";



			$tAsuntoCM = "Nueva reserva web";

         	$dataCM["email"] = $medico->ceme_tcorreo;
         	$dataCM["title"] = $tAsuntoCM;
         	$dataCM["body"] = $tMensajeCM;
         	$dataCM["fecha"] = $fechaActual;
         	$dataCM["id"] = $idReserva;
			Mail::send('agenda.enviarReserva', $dataCM, function($messageCM)use($dataCM) {
         	    $messageCM->to($dataCM["email"], $dataCM["email"])
         	            ->subject($dataCM["title"]);
            	        //->attachData($pdf->output(), 'receta_'.$data["fecha"].'_'.$data["id"].'.pdf');
	         });
            
            //solicitar/{cm}
            return redirect()->route('solicitar.hora',$medico->ceme_tslug)->with('message','Hora registrada!');
//            return redirect()->back()->with('message','Reserva exitosa');
            
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->withInput($request->input())->withErrors(['Error'=>'Ocurrió un error al intentar realizar la reserva, favor intente nuevamente. <pre>'.$e.'</pre>']);
        }
        
	}
	
	public function datosPaciente($nRut,$nCentro){
		$datos = Personas::where('pers_nrut',$nRut)
			->where('ceme_ncod',$nCentro)
			->select('pers_nrut','pers_tdv','pers_tnombres','pers_tpaterno','pers_tmaterno','pers_tinfo','pers_nfono_fijo','pers_nfono_movil',
DB::raw("date_format(pers_fnacimiento,'%d/%m/%Y') as pers_fnacimiento"),
'pers_tcorreo','pers_tdireccion','prev_ncod','gene_ncod','ceme_ncod','pers_ntipo_docto')
			->get();

		return response()->json($datos);
	
	}
	
}
