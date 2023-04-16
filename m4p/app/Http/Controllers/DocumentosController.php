<?php

namespace App\Http\Controllers;

use App\Models\Personas;
use App\Models\PersonasDocumentos;
use App\Models\TiposDocumentos;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;



class DocumentosController extends Controller
{
    public function lista(){
		$nCentroMedico = Auth::user()->ceme_ncod;
        $documentos = PersonasDocumentos::join('personas','personas.pers_nrut','=','personas_documentos.pers_nrut')
        	->where('personas.ceme_ncod',$nCentroMedico)
            ->select('pedo_ncod',DB::raw("case when pers_ntipo_docto = 1 then concat(personas.pers_nrut,'-',pers_tdv) else personas.pers_nrut end as rut"),DB::raw("concat(pers_tnombres,' ',pers_tpaterno) as paciente"),'pedo_tdocumento',DB::raw("date_format(pedo_fdocumento,'%d/%m/%Y') as pedo_fdocumento"),DB::raw("SUBSTRING_INDEX(pedo_tdocumento,'_', -1) as nombre"))
            ->get();

        return view('documentos.lista', compact('documentos'));

    }

    public function crear(){

        $nCentroMedico = Auth::user()->ceme_ncod;

        $pacientes = Personas::where('pers_bpaciente','=',1)
                    ->where('ceme_ncod','=',$nCentroMedico)
                    ->where('personas.pers_nestado','=',1)
                    ->select(DB::raw("concat(pers_tnombres,' ',pers_tpaterno) as nombre"), 'pers_nrut')
                    ->get();

        $tipos_documentos = TiposDocumentos::where('tido_nestado','=',1)
                    ->select('tido_ncod','tido_tnombre')
                    ->get();

        return view('documentos.crear',compact('pacientes','tipos_documentos'));
    }

    public function agregar(Request $request){

        //dd($request);

        $this->validate($request,[
            'pers_nrut' => 'required',
            'tido_ncod' => 'required'
        ]
        ,
        [ 'pers_nrut.required' => 'El campo Paciente es requerido.']);


        $files = $request->file('documentos');
        $bArchivosOK = true;
        $path = storage_path('app/public/');

        if($request->hasFile('documentos')){
            foreach ($files as $file) {

                $fileName = time().rand(0, 1000)."_".pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $this->clean($fileName);
                $fileName = $fileName.'.'.$file->getClientOriginalExtension();

                $bArchivosOK = $bArchivosOK && $file->move($path, $fileName);

                if($bArchivosOK){
                    $sqlDocumentos = "insert into personas_documentos (pers_nrut,pedo_tdocumento,pedo_fdocumento,tido_ncod) values (".$request->pers_nrut.",'".$fileName."',now(),$request->tido_ncod)";
                    DB::statement($sqlDocumentos);
                }
                else{
                    return Redirect::back()->withInput($request->input())->withErrors(['Archivos'=>'No fue posible cargar el/los archivo/s, favor intente nuevamente']);
                }
            }
        }
        else{
            return redirect()->back()->withInput($request->input())->withErrors(['Error'=>'Debe seleccionar un archivo']);
        }

        return redirect()->route('documentos.lista')->with('message','Documento agregado!');
    }

    public function eliminar_documento($id,$docto){
        $path = storage_path('app/public/');
        
        if(file_exists($path.$docto)){
            unlink($path.$docto);
        }

        $sqlEliminar = "delete from personas_documentos where pedo_ncod = ".$id;
        DB::statement($sqlEliminar);

        return Redirect::back()->with('message','Documento eliminado!');

    }


    function clean($string) {
       $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

       return preg_replace('/[^A-Za-z0-9\_\-]/', '', $string); // Removes special chars.
    }
}
