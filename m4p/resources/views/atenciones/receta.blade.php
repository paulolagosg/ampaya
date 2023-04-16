<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Ultra&display=swap" crossorigin="anonymous">

<style type="text/css">
    .page-break {
        page-break-after: always;
    }
    body{
        font-size: 12px;
    }
    @import url('https://fonts.googleapis.com/css2?family=Ultra&display=swap');
</style>
        


<div class="bg-white border-b border-gray-200">
	@php
	function formatoRut( $rut ) {
	    return number_format( substr ( $rut, 0 , -2 ) , 0, "", ".") . substr ( $rut, strlen($rut) -2 , 2 );
	}
	@endphp

    @foreach($datos_atencion as $atencion)
    @php
        $fecha = $atencion->agen_finicio;
        $nombre = $atencion->nombres;
        $edad = $atencion->edad;
        $rut = $atencion->rut;
        
        if($atencion->pers_ntipo_docto == 1){
        	$rut =formatoRut($rut);
        }
        
        $direccion = $atencion->pers_tdireccion;
        $firma = $atencion->pers_tfirma;
        $encabezadoReceta = "<table style='width:100%'>
        <tr><td colspan='4' align='right'>Fecha: <b>".$fecha."</b></td></tr>
        <tr><td>Paciente</td><td colspan='3'>:".strtoupper(str_replace('ñ','Ñ',$nombre))."</td></tr>
        <tr><td>Edad</td><td>:".strtoupper($edad)." AÑOS</td><td >RUT/DNI</td><td nowrap='nowrap'>:".$rut."</td></tr>
        <tr><td>Domicilio</td><td colspan='3'>:".strtoupper(str_replace('ñ','Ñ',$direccion))."</td></tr></table>";
        
        $encabezadoReceta = "<hr style='height: 1px;'>
        <span><b>".strtoupper(str_replace('ñ','Ñ',$nombre))."</b><br/>
        ".$rut."<br/>
        ".strtoupper($edad)." años</span>
        <hr>";


    @endphp

    @endforeach

    @foreach($centro_medico as $cm)
    @php
        $centroMedico =  $cm->ceme_tnombre;
        $direccionCM = $cm->ceme_tdireccion;
        $rutCM = $cm->ceme_trut;
        $descripcionCM = $cm->ceme_tdescripcion;
        $correoCM = $cm->ceme_tcorreo;
        $fonoMovilCM = $cm->ceme_nfono_movil;
        $fonoFijoCM = $cm->ceme_nfono_fijo;
        $logoCM = $cm->ceme_tlogo;
        $tUrl = $cm->ceme_turl;
        $tCiudad = $cm->ceme_tciudad;
        $tInfo = $cm->ceme_tinfo_adicional;
        $tDatosCM = "";
        
        if($cm->ceme_ncod == 2){
        	if($rutCM != ""){
        		$tDatosCM .= "<br/><span style='font-size:9px;'>".$rutCM."</span><br/>";
        	}
        	if($direccionCM != ""){
        		$tDatosCM .= "<span style='font-size:9px;color:gray;'><b>".$descripcionCM."</b></span>";
       	 	}
       	 	
       	 	$tDatosCM .= "<span style='font-size:8px;'>";

			if($tInfo != ""){
        		$tDatosCM .= "<br/>".$tInfo."<br/>";
        	}
       	 	if($direccionCM != ""){
        		$tDatosCM .= "<span style='font-size:9px;'>".$direccionCM.": </span>";
       	 	}
        	
        	if($fonoFijoCM != ""){
        		$tDatosCM .= " +56".$fonoFijoCM;
        	}
        	if($fonoMovilCM != ""){
        		if($fonoFijoCM != ""){
        			$tDatosCM .= " - +56".$fonoMovilCM;
        		}
        		else{
        			$tDatosCM .= " +56".$fonoMovilCM;
        		}
        	}
        	if($correoCM != ""){
        		$tDatosCM .= " - ".$correoCM;
        	}
        	if($tUrl != ""){
        		$tDatosCM .= "<br/>".$tUrl;
        	}
        	if($tCiudad != ""){
        		$tDatosCM .= "<br/><span style='color:gray;'><b><b>".$tCiudad."</b></span>";
        	}
        	
        	$tDatosCM .= "</span>";
        }
        else{
        	if($rutCM != ""){
        		$tDatosCM .= $rutCM."<br/>";
        	}
        	if($direccionCM != ""){
        		$tDatosCM .= $descripcionCM;
       	 	}
        	if($direccionCM != ""){
        		$tDatosCM .= "<br/>".$direccionCM;
        	}
        	if($fonoFijoCM != ""){
        		$tDatosCM .= "<br/>+56".$fonoFijoCM;
        	}
        	if($fonoMovilCM != ""){
        		$tDatosCM .= "<br/>+56".$fonoMovilCM;
        	}
        	if($correoCM != ""){
        		$tDatosCM .= "<br/>".$correoCM;
        	}
        	if($tUrl != ""){
        		$tDatosCM .= "<br/>".$tUrl;
        	}
        }
        
    	$encabezadoCM = "<table style='width:100%'>
        <tr>
            <!--td valign='top' class='text-center' style='width:70%'>
                <span style='font-size: 11px;font-family:Ultra;'><strong>".$centroMedico."</strong></span>
               
                    ".$tDatosCM."
               
            </td-->
            <td valign='top' class='text-center' style='width:100%'><img src='".url("/")."/img/".$logoCM."' style='width:100%'></td>
        </tr>
    </table>";
    	
    $firma ="<div style='position: absolute; text-align: right;width:100%'><img src='".url("/")."/img/".$firma."'> </div>";
    
    @endphp
    @endforeach
    @php
    $tDiagnostico = "";
    if(count($diagnosticos) > 0 || $atencion->aten_totros != ""){
        $tDiagnostico = "<table><tr>
            <td valign='top'>Diagnóstico(s)</td>
            <td valign='top'>:";
                foreach($diagnosticos as $d){
                    $tDiagnostico .= $d->diag_tdescripcion."<br/>";
                }
                $tDiagnostico .= strtoupper($atencion->aten_totros);
        $tDiagnostico .= "</td>
        </tr></table>";
    }
    $tDiagnostico2 = "";
	@endphp

    @if(count($laboratorios) > 0 || count($imagenes) > 0 || count($nucleares) > 0 || $atencion->aten_tfarmacos != "" || $atencion->aten_tindicaciones != "" || $atencion->aten_tlabo_otros != "" || $atencion->aten_tima_otros != "" || $atencion->aten_tnuclear_otros != "")
    <!-- laboratorio -->
    	@if(count($laboratorios) > 0 || $atencion->aten_tlabo_otros != "")
    		{!! $encabezadoCM !!}
    		{!! $encabezadoReceta !!}
    		{!! $tDiagnostico2 !!}
    		<p style='font-family:Ultra'>Rp:</p>
    		<table >
        		<tr>
            		<td valign="top">
           			@php
            			$filasParametros = count($laboratorios);
           				$filasReceta = explode('</p>',$atencion->aten_tlabo_otros);
           				$filasTotal = intval($filasParametros) + intval(count($filasReceta));
						$nParametros = 1;
						foreach($laboratorios as $l){
            				if(($nParametros % 8) > 0){
            					echo "<p>".strtoupper($l->labo_tnombre)."</p>";            					
            				}
            				else{
            					echo "<p>".strtoupper($l->labo_tnombre)."</p>";
            					echo "$firma</td>
				    				    </tr>
				    			</table>
              					<div class='page-break'></div>";
              					echo $encabezadoCM.$encabezadoReceta.$tDiagnostico;
              					echo "<p style='font-family:Ultra'>Rp:</p>
    			 				<table >
			        				<tr>
				            			<td valign='top'>";
            				}
            				$nParametros++;
            			}
            			$nFilasDisponibles = ($nParametros - 8);
            			if($nFilasDisponibles < 0){
            				$nFilasDisponibles = 1;
            			}
            			if(($nFilasDisponibles % 9) > 0){
            				if(count($filasReceta) > 0){
			           			echo $filasReceta[0];
			           			$nFilasDisponibles++;
           					}
           					for($n=1;$n < count($filasReceta);$n++){
           						if(intval((intval($n)+intval($nFilasDisponibles)) % 8) > 0){
	           						echo $filasReceta[$n];
	           					}
	           					else{
	           						echo $filasReceta[$n];
            						echo "$firma</td>
				    				    </tr>
				    				</table>
              						<div class='page-break'></div>";
              						echo $encabezadoCM.$encabezadoReceta.$tDiagnostico;
              						echo "<p style='font-family:Ultra'>Rp:</p>
    			 					<table >
			        					<tr>
			        						<td valign='top'>";
	           					}
           					}
           					echo $firma."</td>
				    				    </tr>
				    			</table>";
            			}
            			
           				echo $firma."</td>
				    				    </tr>
				    			</table>";
		        	@endphp 
    		</table>
	    @endif
	    <!-- imagenes -->
	    @if(count($imagenes) > 0 || $atencion->aten_tima_otros != "")
		    @if(count($laboratorios) > 0 || $atencion->aten_tlabo_otros != "" )
	    	<div class='page-break'></div>
	    	@endif
    		{!! $encabezadoCM !!}
    		{!! $encabezadoReceta !!}
    		{!! $tDiagnostico2 !!}
    		<p style='font-family:Ultra'>Rp:</p>
    		<table >
        		<tr>
            		<td valign="top">
           			@php
           				$filasParametros = 0;
           				$filasReceta = 0;
           				$filasTotal = 0;
						$nParametros = 0;
            			$nFilasDisponibles = 0;
            			
            			$filasParametros = count($imagenes);
           				$filasReceta = explode('</p>',$atencion->aten_tima_otros);
           				$filasTotal = intval($filasParametros) + intval(count($filasReceta));
						$nParametros = 1;
						foreach($imagenes as $i){
            				if(($nParametros % 8) > 0){
            					echo "<p>".strtoupper($i->iman_tnombre)."</p>";            					
            				}
            				else{
            					echo "<p>".strtoupper($i->iman_tnombre)."</p>";
            					echo "$firma</td>
				    				    </tr>
				    			</table>
              					<div class='page-break'></div>";
              					echo $encabezadoCM.$encabezadoReceta.$tDiagnostico;
              					echo "<p style='font-family:Ultra'>Rp:</p>
    			 				<table >
			        				<tr>
				            			<td valign='top'>";
            				}
            				$nParametros++;
            			}
            			$nFilasDisponibles = ($nParametros - 8);
            			if($nFilasDisponibles < 0){
            				$nFilasDisponibles = 1;
            			}
            			if(($nFilasDisponibles % 9) > 0){
            				if(count($filasReceta) > 0){
			           			echo $filasReceta[0];
			           			$nFilasDisponibles++;
           					}
           					for($n=1;$n < count($filasReceta);$n++){
           						if(intval((intval($n)+intval($nFilasDisponibles)) % 8) > 0){
	           						echo $filasReceta[$n];
	           					}
	           					else{
	           						echo $filasReceta[$n];
            						echo "$firma</td>
				    				    </tr>
				    				</table>
              						<div class='page-break'></div>";
              						echo $encabezadoCM.$encabezadoReceta.$tDiagnostico;
              						echo "<p style='font-family:Ultra'>Rp:</p>
    			 					<table >
			        					<tr>
			        						<td valign='top'>";
	           					}
           					}
           					echo $firma."</td>
				    				    </tr>
				    			</table>";
            			}
            			
           				echo $firma."</td>
				    				    </tr>
				    			</table>";
		        	@endphp 
    		</table>
	    @endif
	    <!--  nuclerar -->
	    @if(count($nucleares) > 0 || $atencion->aten_tnuclear_otros != "" )
    	 	@if(count($laboratorios) > 0 || count($imagenes) > 0 || $atencion->aten_tlabo_otros != "" || $atencion->aten_tima_otros != "" )
	    	<div class='page-break'></div>
	    	@endif
    		{!! $encabezadoCM !!}
    		{!! $encabezadoReceta !!}
    		{!! $tDiagnostico2 !!}
    		<p style='font-family:Ultra'>Rp:</p>
    		<table >
        		<tr>
            		<td valign="top">
           			@php
           				$filasParametros = 0;
           				$filasReceta = 0;
           				$filasTotal = 0;
						$nParametros = 0;
            			$nFilasDisponibles = 0;
            			
            			$filasParametros = count($nucleares);
           				$filasReceta = explode('</p>',$atencion->aten_tnuclear_otros);
           				$filasTotal = intval($filasParametros) + intval(count($filasReceta));
						$nParametros = 1;
						foreach($nucleares as $nu){
            				if(($nParametros % 8) > 0){
            					echo "<p>".strtoupper($nu->nucl_tnombre)."</p>";            					
            				}
            				else{
            					echo "<p>".strtoupper($nu->nucl_tnombre)."</p>";
            					echo "$firma</td>
				    				    </tr>
				    			</table>
              					<div class='page-break'></div>";
              					echo $encabezadoCM.$encabezadoReceta.$tDiagnostico;
              					echo "<p style='font-family:Ultra'>Rp:</p>
    			 				<table >
			        				<tr>
				            			<td valign='top'>";
            				}
            				$nParametros++;
            			}
            			$nFilasDisponibles = ($nParametros - 8);
            			if($nFilasDisponibles < 0){
            				$nFilasDisponibles = 1;
            			}
            			if(($nFilasDisponibles % 9) > 0){
            				if(count($filasReceta) > 0){
			           			echo $filasReceta[0];
			           			$nFilasDisponibles++;
           					}
           					for($n=1;$n < count($filasReceta);$n++){
           						if(intval((intval($n)+intval($nFilasDisponibles)) % 8) > 0){
	           						echo $filasReceta[$n];
	           					}
	           					else{
	           						echo $filasReceta[$n];
            						echo "$firma</td>
				    				    </tr>
				    				</table>
              						<div class='page-break'></div>";
              						echo $encabezadoCM.$encabezadoReceta.$tDiagnostico;
              						echo "<p style='font-family:Ultra'>Rp:</p>
    			 					<table >
			        					<tr>
			        						<td valign='top'>";
	           					}
           					}
           					echo $firma."</td>
				    				    </tr>
				    			</table>";
            			}
            			
           				echo $firma."</td>
				    				    </tr>
				    			</table>";
		        	@endphp 
    		</table>
	    @endif
	    <!-- farmacos -->
	    @if($atencion->aten_tfarmacos != "")
    		@if(count($laboratorios) > 0 || count($imagenes) > 0 || count($nucleares) > 0 || $atencion->aten_tlabo_otros != "" || $atencion->aten_tima_otros != "" || $atencion->aten_tnuclear_otros != "")
	    	<div class='page-break'></div>
	    	@endif
    		{!! $encabezadoCM !!}
    		{!! $encabezadoReceta !!}
    		{!! $tDiagnostico2 !!}
    		<p style='font-family:Ultra'>Rp:</p>
    		<table >
        		<tr>
            		<td valign="top">
           			@php
           				$filasParametros = 0;
           				$filasReceta = 0;
           				$filasTotal = 0;
						$nParametros = 0;
            			$nFilasDisponibles = 0;
            			
           				$filasReceta = explode('</p>',$atencion->aten_tfarmacos);
           				$filasTotal = intval($filasParametros) + intval(count($filasReceta));
						$nParametros = 1;

            			$nFilasDisponibles = ($nParametros - 8);
            			if($nFilasDisponibles < 0){
            				$nFilasDisponibles = 1;
            			}
            			if(($nFilasDisponibles % 9) > 0){
            				if(count($filasReceta) > 0){
			           			echo $filasReceta[0];
			           			$nFilasDisponibles++;
           					}
           					for($n=1;$n < count($filasReceta);$n++){
           						if(intval((intval($n)+intval($nFilasDisponibles)) % 8) > 0){
	           						echo $filasReceta[$n];
	           					}
	           					else{
	           						echo $filasReceta[$n];
            						echo "$firma</td>
				    				    </tr>
				    				</table>
              						<div class='page-break'></div>";
              						echo $encabezadoCM.$encabezadoReceta.$tDiagnostico;
              						echo "<p style='font-family:Ultra'>Rp:</p>
    			 					<table >
			        					<tr>
			        						<td valign='top'>";
	           					}
           					}
           					echo $firma."</td>
				    				    </tr>
				    			</table>";
            			}
            			
           				echo $firma."</td>
				    				    </tr>
				    			</table>";
		        	@endphp 
    		</table>
    	@endif
    	<!-- indicacioes -->
    	@if($atencion->aten_tindicaciones != "")
    		@if(count($laboratorios) > 0 || count($imagenes) > 0 || count($nucleares) > 0 || $atencion->aten_tfarmacos != "" || $atencion->aten_tlabo_otros != "" || $atencion->aten_tima_otros != "" || $atencion->aten_tnuclear_otros != "")
	    	<div class='page-break'></div>
	    	@endif
    		{!! $encabezadoCM !!}
    		{!! $encabezadoReceta !!}
    		{!! $tDiagnostico2 !!}
    		<p style='font-family:Ultra'>Rp:</p>
    		<table >
        		<tr>
            		<td valign="top">
           			@php
           				$filasParametros = 0;
           				$filasReceta = 0;
           				$filasTotal = 0;
						$nParametros = 0;
            			$nFilasDisponibles = 0;
            			
           				$filasReceta = explode('</p>',$atencion->aten_tindicaciones);
           				$filasTotal = intval($filasParametros) + intval(count($filasReceta));
						$nParametros = 1;

            			$nFilasDisponibles = ($nParametros - 8);
            			if($nFilasDisponibles < 0){
            				$nFilasDisponibles = 1;
            			}
            			if(($nFilasDisponibles % 9) > 0){
            				if(count($filasReceta) > 0){
			           			echo $filasReceta[0];
			           			$nFilasDisponibles++;
           					}
           					for($n=1;$n < count($filasReceta);$n++){
           						if(intval((intval($n)+intval($nFilasDisponibles)) % 8) > 0){
	           						echo $filasReceta[$n];
	           					}
	           					else{
	           						echo $filasReceta[$n];
            						echo "$firma</td>
				    				    </tr>
				    				</table>
              						<div class='page-break'></div>";
              						echo $encabezadoCM.$encabezadoReceta.$tDiagnostico;
              						echo "<p style='font-family:Ultra'>Rp:</p>
    			 					<table >
			        					<tr>
			        						<td valign='top'>";
	           					}
           					}
           					echo $firma."</td>
				    				    </tr>
				    			</table>";
            			}
            			
           				echo $firma."</td>
				    				    </tr>
				    			</table>";
		        	@endphp 
    		</table>
    	@endif
    @endif
</div>
