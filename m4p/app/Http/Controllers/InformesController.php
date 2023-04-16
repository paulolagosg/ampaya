<?php

namespace App\Http\Controllers;

use App\Models\CentrosMedicos;
use App\Models\Personas;
use App\Models\PersonasInformes;
use App\Models\TiposInformes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use PDF;


class InformesController extends Controller
{
    public function lista(){

        $nCentroMedico = Auth::user()->ceme_ncod;
        
        $informes = PersonasInformes::join('personas','personas.pers_nrut','=','personas_informes.pers_nrut')
            ->join('tipos_informes','personas_informes.tiin_ncod','=','tipos_informes.tiin_ncod')
            ->where('personas.ceme_ncod','=',$nCentroMedico)
            ->select('pein_ncod',DB::raw("case when pers_ntipo_docto = 1 then concat(personas.pers_nrut,'-',pers_tdv) else personas.pers_nrut end as rut"),DB::raw("concat(pers_tnombres,' ',pers_tpaterno) as paciente"),DB::raw("date_format(pein_finforme,'%d/%m/%Y') as pein_finforme"),'pein_tnombre','tiin_tnombre')
            ->get();

        return view('informes.lista', compact('informes'));

    }


    public function crear(){

        $nCentroMedico = Auth::user()->ceme_ncod;

        $pacientes = Personas::where('pers_bpaciente','=',1)
                    ->where('ceme_ncod','=',$nCentroMedico)
                    ->where('personas.pers_nestado','=',1)
                    ->select(DB::raw("concat(pers_tnombres,' ',pers_tpaterno) as nombre"), 'pers_nrut')
                    ->get();

        $tipos_informes = TiposInformes::where('tiin_nestado','=',1)
                    ->select('tiin_ncod','tiin_tnombre')
                    ->get();

        $medico = Personas::where('user_id','=',Auth::user()->id)->get();
        $medico = $medico[0]->pers_nrut;


        return view('informes.crear',compact('pacientes','tipos_informes','medico'));
    }

    public function agregar(Request $request){


        $this->validate($request,[
            'pers_nrut' => 'required',
            'tiin_ncod' => 'required',
            'pein_tnombre' => 'required|min:5',
            'pein_tinforme' => 'required|min:50',
        ]
        ,
        [ 'pers_nrut.required' => 'El campo Paciente es requerido.']);


        $informe = new PersonasInformes();

        $informe->pers_nrut = $request->pers_nrut;
        $informe->pein_tnombre = $request->pein_tnombre;
        $informe->tiin_ncod = $request->tiin_ncod;
        $informe->pein_tinforme = $request->pein_tinforme;
        $informe->pers_nrut_medico = $request->pers_nrut_medico;
        $informe->pein_finforme = now();
        $informe->save();

        return redirect()->route('informes.lista')->with('message','Informe agregado');
    }

    public function eliminar($id){
        try{
            $datos = PersonasInformes::where('pein_ncod', '=', $id)->firstOrFail();
        }
        catch(ModelNotFoundException $e){
            abort(404, __('Sorry, the page you are looking for is not available.'));    
        }


        $deleted = DB::table('personas_informes')->where('pein_ncod', '=', $id)->delete();

        return Redirect::back()->with('message','Informe eliminado');

    }

    public function editar($id){
        $nCentroMedico = Auth::user()->ceme_ncod;

        $pacientes = Personas::where('pers_bpaciente','=',1)
                    ->where('ceme_ncod','=',$nCentroMedico)
                    ->where('personas.pers_nestado','=',1)
                    ->select(DB::raw("concat(pers_tnombres,' ',pers_tpaterno) as nombre"), 'pers_nrut')
                    ->get();

        $tipos_informes = TiposInformes::where('tiin_nestado','=',1)
                    ->select('tiin_ncod','tiin_tnombre')
                    ->get();

        $datos_informe = PersonasInformes::where('pein_ncod','=',$id)
                    ->get();

        return view('informes.editar',compact('pacientes','tipos_informes','datos_informe','id'));

    }

    public function modificar(Request $request){
        //dd($request);
        $this->validate($request,[
            'tiin_ncod' => 'required',
            'pein_tnombre' => 'required|min:5',
            'pein_tinforme' => 'required|min:50',
        ]);

        $sqlUpdate = "update personas_informes set pein_tnombre = '".$request->pein_tnombre."', tiin_ncod = ".$request->tiin_ncod.", pein_tinforme = '".$request->pein_tinforme."' where pein_ncod = ".$request->pein_ncod;


        DB::beginTransaction();

        try {
            DB::statement($sqlUpdate);

            DB::commit();
            return redirect()->route('informes.lista')->with('message','Registro modificado!');
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->withInput($request->input())->withErrors(['Error'=>'Ocurrió un error al intentar modificar, favor intente nuevamente'.$e]);
        }
    }

    public function ver($id){
        try{
            $datos = PersonasInformes::where('pein_ncod', '=', $id)->firstOrFail();
            
        }
        catch(ModelNotFoundException $e){
            return Redirect::back()->withErrors(['Informe'=>'No existe un informe asociado']);  
        }

		$nCentroMedico = Auth::user()->ceme_ncod;

        $datos_informe = PersonasInformes::join('personas','personas.pers_nrut','=','personas_informes.pers_nrut')
                    ->join('tipos_informes','tipos_informes.tiin_ncod','=','personas_informes.tiin_ncod')
                    ->join('personas as m','m.pers_nrut','=','personas_informes.pers_nrut_medico')
                    ->where('pein_ncod','=',$id)
                    ->where('personas.ceme_ncod',$nCentroMedico)
                    ->select(DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno,' ',coalesce(personas.pers_tmaterno,'')) as nombres"),DB::raw("case when personas.pers_ntipo_docto = 1 then concat(personas.pers_nrut,'-',personas.pers_tdv) else personas.pers_nrut end as rut"),'personas.pers_tcorreo','personas.pers_ncod','personas.pers_nestado','pein_tnombre','pein_tinforme','tiin_tnombre',DB::raw("date_format(pein_finforme,'%d/%m/%Y') as pein_finforme"),'m.pers_tfirma')
                    ->get();

        
        $centro_medico = CentrosMedicos::where('ceme_ncod','=',$nCentroMedico)
                    ->get();

        $data = [
            'centro_medico' => $centro_medico,
            'datos_informe' => $datos_informe
        ]; 

        $pdf = PDF::loadView('informes.informePDF', $data);
        return $pdf->stream('informe_'.$id.'.pdf');
    }

    public function enviar($id){
        try{
            $datos = PersonasInformes::where('pein_ncod', '=', $id)->firstOrFail();
            
        }
        catch(ModelNotFoundException $e){
            return Redirect::back()->withErrors(['Informe'=>'No existe un informe asociado']);  
        }

		$nCentroMedico = Auth::user()->ceme_ncod;
        $fechaActual = date('Ymd_His');
        $datos_informe = PersonasInformes::join('personas','personas.pers_nrut','=','personas_informes.pers_nrut')
                    ->join('tipos_informes','tipos_informes.tiin_ncod','=','personas_informes.tiin_ncod')
                    ->join('personas as m','m.pers_nrut','=','personas_informes.pers_nrut_medico')
                    ->where('pein_ncod','=',$id)
                    ->where('personas.ceme_ncod',$nCentroMedico)
                    ->select(DB::raw("concat(personas.pers_tnombres,' ',personas.pers_tpaterno,' ',coalesce(personas.pers_tmaterno,'')) as nombres"),DB::raw("case when personas.pers_ntipo_docto = 1 then concat(personas.pers_nrut,'-',personas.pers_tdv) else personas.pers_nrut end as rut"),'personas.pers_tcorreo','personas.pers_ncod','personas.pers_nestado','pein_tnombre','pein_tinforme','tiin_tnombre',DB::raw("date_format(pein_finforme,'%d/%m/%Y') as pein_finforme"),'m.pers_tfirma')
                    ->get();

        $centro_medico = CentrosMedicos::where('ceme_ncod','=',$nCentroMedico)
                    ->get();

        $data = [
            'centro_medico' => $centro_medico,
            'datos_informe' => $datos_informe
        ]; 

        $pdf = PDF::loadView('informes.informePDF', $data);

		
        $tMensaje = "<h3>Estimado/a ".$datos_informe[0]->nombres."</h3><p>Adjunto encontrará informe/certificado con fecha ".$datos_informe[0]->pein_finforme."</p><br/>Saludos<br/> ".$centro_medico[0]->ceme_tnombre."<p><strong>NOTA:</strong>Este mensaje fue generado por un sistema de correo automático, por lo tanto, no debe responderlo. Este mensaje y/o documentos adjuntos son confidenciales y están destinados a la(s) persona(s) a la que han sido enviados. Pueden contener información privada y confidencial, cuya difusión se encuentre legalmente prohibida. Si usted no es el destinatario, por favor notifique de inmediato al remitente y elimine el mensaje de sus carpetas y/o archivos.</p>";
        $tAsunto = "Informe/Certificado médico.";

        $data["email"] = $datos_informe[0]->pers_tcorreo;
        $data["title"] = $tAsunto;
        $data["body"] = $tMensaje;
        $data["fecha"] = $fechaActual;
        $data["id"] = $id;

        //dd($data);

        Mail::send('informes.enviarInforme', $data, function($message)use($data, $pdf) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"])
                    ->attachData($pdf->output(), 'informe_'.$data["fecha"].'_'.$data["id"].'.pdf');
        });

        return Redirect::back()->with('message','Informe enviado');
        //return $pdf->stream('informe_'.$fechaActual.'_'.$id.'.pdf');


    }

}
