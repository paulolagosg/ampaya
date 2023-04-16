<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Atendiendo a 
            @foreach($datos_paciente as $dp)
                <strong>{{ $dp->rut}} / {{$dp->nombre}} / {{$dp->edad}} Años </strong>
            @endforeach
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    @if(session()->has('message'))
                      <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                          {{ session()->get('message') }}
                      </div>
                    @endif
                    <form id="fatencion" name="atencion" method="POST" action="{{ route('atenciones.guardar')}}"  enctype="multipart/form-data">
                        @csrf
                        @foreach($datos_atencion as $atencion)
                        
                        <div class="col-md-12">  
                            <div id="tabs">
                                <ul>
                                    <li><a href="#tabs-1">Antecedentes</a></li>
                                    <li><a href="#tabs-2">Síntomas y diagnóstico</a></li>
                                    <li><a href="#tabs-3">Indicaciones</a></li>
                                    <li><a href="#tabs-5">Atenciones anteriores</a></li>
                                </ul>
                                <div id="tabs-1">
                                    <div class="form-row col-md-12"style="display: table;">
                                        <div class="col-md-2 text-bold">
                                            <strong>Antecedentes</strong>
                                        </div>
                                        <div class="col-md-10"> 
                                            <textarea class="form-control" id="pers_tinfo" name="pers_tinfo">{{ old('pers_tinfo',$dp->pers_tinfo)}}</textarea>
                                            <input type="hidden" name="pers_nrut" id="pers_nrut" value="{{ $datos_paciente[0]->pers_nrut }}">
                                            <input type="hidden" name="aten_ncod" id="aten_ncod" value="{{ $nAtencion }}">
                                            <input type="hidden" name="diagnostico_hidden" id="diagnostico_hidden" value="{{ $atencion->aten_tdiagnostico }}">
                                            <input type="hidden" name="laboratorio_hidden" id="laboratorio_hidden" value="{{ $atencion->aten_tlaboratorio }}">
                                            <input type="hidden" name="nuclear_hidden" id="nuclear_hidden" value="{{ $atencion->aten_tnuclear }}">
                                            <input type="hidden" name="imagenes_hidden" id="imagenes_hidden" value="{{ $atencion->aten_timagenes }}">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div id="tabs-2">
                                    <div class="form-row col-md-12" style="display: table;">
                                        <div class="col-md-2 text-bold">
                                            <strong>Síntomas</strong>
                                        </div>
                                        <div class="col-md-10"> 
                                            <textarea class="form-control" id="aten_tsintomas" name="aten_tsintomas">{{ old('aten_tsintomas',$atencion->aten_tsintomas)}}</textarea>
                                        </div>
                                    </div>
                                    <p>&nbsp;</p>
                                    <div class="form-row col-md-12" style="display: table;">
                                        <div class="col-md-2 text-bold">
                                            <strong>Diagnóstico</strong>
                                        </div>
                                        <div class="col-md-10"> 
                                            <select class="smultiple form-control" id="aten_tdiagnostico" name="aten_tdiagnostico[]" multiple="multiple" >
                                                <option value="">Seleccione</option>
                                            @foreach($diagnosticos as $diag)
                                                <option value="{{ $diag->diag_ncod}}"




                                                    >{{ $diag->diag_tcodigo}} - {{ $diag->diag_tdescripcion}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <p>&nbsp;</p>
                                    <div class="form-row col-md-12" style="display: table;">
                                        <div class="col-md-2 text-bold">
                                            <strong>Otro Diagnóstico</strong>
                                        </div>
                                        <div class="col-md-10"> 
                                            <input class="form-control" type="text" name="otros" id="otros" value="{{ $atencion->aten_totros}}">
                                        </div>
                                    </div>

                                </div>
                                <div id="tabs-3">
                                    <div class="form-row col-md-12" style="display: table;">
                                        <div class="col-md-4 text-bold">
                                            <strong>Exámenes de laboratorio</strong>
                                        </div>
                                        <div class="col-md-8"> 
                                            <select class="smultiple form-control" id="aten_tlaboratorio" name="aten_tlaboratorio[]" multiple="multiple" >
                                                <option value="">Seleccione</option>
                                            @foreach($laboratorios as $labo)
                                                <option value="{{ $labo->labo_ncod}}">{{ $labo->labo_tnombre}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 text-bold">
                                            <strong>Otros Exámenes de laboratorio</strong>
                                        </div>
                                        <div class="col-8">
                                        	<textarea id="aten_tlabo_otros" name="aten_tlabo_otros">{{$atencion->aten_tlabo_otros}}</textarea>
                                        </div>
                                    </div>
                                    <p>&nbsp;</p>
                                    <div class="form-row col-md-12" style="display: table;">
                                        <div class="col-md-4 text-bold">
                                            <strong>Imagenología</strong>
                                        </div>
                                        <div class="col-md-8"> 
                                            <select class="smultiple form-control" id="aten_timagenes" name="aten_timagenes[]" multiple="multiple" >
                                                <option value="">Seleccione</option>
                                            @foreach($imagenes as $iman)
                                                <option value="{{ $iman->iman_ncod}}">{{ $iman->iman_tnombre}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 text-bold">
                                            <strong>Otros Imagenología</strong>
                                        </div>
                                        <div class="col-8">
                                        	<textarea id="aten_tima_otros" name="aten_tima_otros">{{$atencion->aten_tima_otros}}</textarea>
                                        </div>
                                    </div>
                                    <p>&nbsp;</p>
                                    <div class="form-row col-md-12" style="display: table;">
                                        <div class="col-md-4 text-bold">
                                            <strong>Nuclear</strong>
                                        </div>
                                        <div class="col-md-8"> 
                                            <select class="smultiple form-control" id="aten_tnuclear" name="aten_tnuclear[]" multiple="multiple" >
                                                <option value="">Seleccione</option>
                                            @foreach($nucleares as $nucl)
                                                <option value="{{ $nucl->nucl_ncod}}">{{ $nucl->nucl_tnombre}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 text-bold">
                                            <strong>Otros Nuclear</strong>
                                        </div>
                                        <div class="col-8">
                                        	<textarea id="aten_tnuclear_otros" name="aten_tnuclear_otros">{{$atencion->aten_tnuclear_otros}}</textarea>
                                        </div>
                                    </div>
                                    <p>&nbsp;</p>
                                    <div class="form-row col-md-12" style="display: table;">
                                        <div class="col-md-2 text-bold">
                                            <strong>Fármacos</strong>
                                        </div>
                                        <div class="col-md-10"> 
                                            <textarea name="aten_tfarmacos" id="aten_tfarmacos" class="form-control">{{ old('aten_tfarmacos',$atencion->aten_tfarmacos)}}</textarea>
                                        </div>
                                    </div>
                                    <p>&nbsp;</p>
                                    <div class="form-row col-md-12" style="display: table;">
                                        <div class="col-md-2 text-bold">
                                            <strong>Otras</strong>
                                        </div>
                                        <div class="col-md-10"> 
                                            <textarea name="aten_tindicaciones" id="aten_tindicaciones" class="form-control">{{ old('aten_tindicaciones',$atencion->aten_tindicaciones)}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="tabs-5">
                                   <div class="table-responsive">
                        <table id="orderTable" class="table table-striped table-bordered display" style="width:100%">
                            <thead>
                                <tr>
                                <th class="text-center">RUT/DNI</th>
                                <th class="text-center">Paciente</th>
                                <th class="text-center">Fecha Cita</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($atenciones as $dato)
                                <tr>
                                    <td class="text-right">{{ $dato->rut}}</td>
                                    <td>{{ $dato->nombre}}</td>
                                    <td class="text-center">{{ $dato->aten_ffecha  }}</td>
                                    <td class="text-center">
                                        {{ $dato->esat_tnombre }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('atenciones.ver_paciente',$dato->aten_ncod) }}" target="_blank"><i class="fa fa-eye" title="Ver atención"></i></a>&nbsp;
                                        <a href="{{ route('documentos.receta',$dato->aten_ncod) }}" target="_blank"><i class="fa fa-file-text" title="Ver receta"></i></a>
                                        &nbsp;
                                        <!--- a href="{{ route('documentos.enviar',$dato->aten_ncod) }}"><i class="fa fa fa-send" title="Enviar receta"></i></a -->  
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                                </div>
                            </div>
                            <div class="form-row">
                                
                            </div>
                        </div>
                        
	                        <p class="text-right p-4">
    	                        ¿Finalizar atención?&nbsp;<input type="checkbox" name="finalizar" id="finalizar" value="1">&nbsp;
        	                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;Guardar</button>&nbsp;
            	                <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('dashboard')}}';"><i class="fa fa-arrow-left"></i>&nbsp;Volver</button>
                	        </p>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<link rel="stylesheet" href="{{ asset('css/jquery-ui.css')}}">
<script src="//code.jquery.com/ui/1.13.1/jquery-ui.js"></script>  
<link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.tiny.cloud/1/779rqxm1bq47n7i0pgujhz6l5821gsc2kf4s0q717wrvtyde/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<!-- script src="{{ asset('/js/picture.js')}}"></script -->  

<script> 
function initMCEexact(e){
    tinyMCE.init({
        selector : 'textarea#'+e,
        menu: {
            file: { title: 'File', items: ' ' },
            edit: { title: 'Edicion', items: 'undo redo | cut copy paste | selectall | searchreplace' },
            view: { title: 'View', items: ' ' },
            insert: { title: 'Insertar', items: ' ' },
            format: { title: 'Formato', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align lineheight | forecolor backcolor | removeformat' },
            tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount' },
            table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' },
            help: { title: 'Help', items: 'help' }
        }
    });
}
$(document).ready(function() {
    $('.smultiple').select2();
    $( function() {
        $( "#tabs" ).tabs();
    } );
    initMCEexact("pers_tinfo");
    initMCEexact("aten_tsintomas");
    initMCEexact("aten_tfarmacos");
    initMCEexact("aten_tindicaciones");
    initMCEexact("aten_tlabo_otros");
	initMCEexact("aten_tima_otros");
	initMCEexact("aten_tnuclear_otros");
    
    

	if($("#diagnostico_hidden").val() != ''){
	    $('#aten_tdiagnostico').val($("#diagnostico_hidden").val());
    	$('#aten_tdiagnostico').trigger('change');
    }
	if($("#laboratorio_hidden").val()  != ""){
    	$('#aten_tlaboratorio').val($("#laboratorio_hidden").val());
	    $('#aten_tlaboratorio').trigger('change');
	}
	if($("#imagenes_hidden").val() != ""){
    	$('#aten_timagenes').val($("#imagenes_hidden").val());
	    $('#aten_timagenes').trigger('change');
	}
	if($("#nuclear_hidden").val() != ""){
	    $('#aten_tnuclear').val($("#nuclear_hidden").val());
	    $('#aten_tnuclear').trigger('change');
	}
});
</script>
