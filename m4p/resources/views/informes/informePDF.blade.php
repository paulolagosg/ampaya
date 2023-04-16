<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<style>
    .page-break {
        page-break-after: always;
    }
</style>


<div class="p-6 bg-white border-b border-gray-200">
    <table style="width:100%">
    @foreach($centro_medico as $cm)
    	@php
    		$centroMedico =  $cm->ceme_tnombre;
        	$direccionCM = $cm->ceme_tdireccion;
        	$descripcionCM = $cm->ceme_tdescripcion;
        	$correoCM = $cm->ceme_tcorreo;
        	$fonoMovilCM = $cm->ceme_nfono_movil;
        	$fonoFijoCM = $cm->ceme_nfono_fijo;
        	$logoCM = $cm->ceme_tlogo;
        	$tUrl = $cm->ceme_turl;
        	$tDatosCM = "";
        
        	if($direccionCM != ""){
        		$tDatosCM .= "<br/>".$descripcionCM;
       		}
        	if($direccionCM != ""){
        		$tDatosCM .= "<br/>".$direccionCM;
        	}
        	if($correoCM != ""){
        		$tDatosCM .= "<br/>".$correoCM;
        	}
        	if($fonoFijoCM != ""){
        		$tDatosCM .= "<br/>+56".$fonoFijoCM;
        	}
        	if($fonoMovilCM != ""){
        		$tDatosCM .= "<br/>+56".$fonoMovilCM;
        	}
        	if($tUrl != ""){
        		$tDatosCM .= "<br/>".$tUrl;
        	}
        	
        	
        	
        	
        	
        	
        	
    	@endphp
        <tr>
            <!--td valign="top" class="text-center" style="width:50%">
                <label style="font-size: 20px"><strong>{{ $cm->ceme_tnombre }}</strong></label><br/>
                <label style="font-size: 12px">
                    {!! $tDatosCM !!}<br/>
                </label>
            </td-->
            <td valign="top" class="text-center" style="width:100%"><img src="{{url('/')}}/img/{{$cm->ceme_tlogo}}" style="width:100%"></td>
        </tr>
    @endforeach
    </table>
    
    @foreach($datos_informe as $di)
    {!! $di->pein_tinforme !!}
    @if($di->pers_tfirma != "")
    <div class="text-center" style="position:absolute; width:100%">
        <img src="{{url('/')}}/img/{{$di->pers_tfirma}}">
    </div>
    @endif
    @endforeach
  </main>  
</div>
