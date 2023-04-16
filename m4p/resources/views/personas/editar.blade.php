<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pacients') }}
        </h2>
    </x-slot>
    @foreach($datos as $dato)
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  shadow-sm sm:rounded-lg">
            	<p class="p-4">
            		<a href="{{ route('personas.lista')}}">
            			<i class="fa fa-arrow-circle-left"></i>&nbsp;Volver al listado de pacientes
            		</a>
            	</p>
                <div class="p-6 bg-white border-b border-gray-200">
                	<x-auth-validation-errors class="mb-4" :errors="$errors" />
                	<form  id="fPacientes" method="POST" action="{{ route('personas.modificar') }}">
                		@csrf
                		<div class="form-row col-md-12 m-b-10">  
				            <div class="col-md-3" style="display: table;">
				                <div class="col-md-6 text-bold"><strong>Tipo Documento</strong><span class="requerido">*</span></div>
				                <div class="col-md-6"> 
				                	<label>
				                		@if($dato->pers_ntipo_docto == 1)
				                			RUT
				                		@else
				                			DNI
				                		@endif
				                	</label>
				                	<input type="hidden" name="pers_ntipo_docto" id="pers_ntipo_docto" value="{{$dato->pers_ntipo_docto}}">
				                </div>
				            </div>
				            <div class="col-md-3" style="display: table;">
				                <div class="col-md-6 text-bold"><strong>Número documento</strong><span class="requerido">*</span></div>
				                <div class="col-md-6"> 
				                	<label>{{$dato->rut}}</label>
				                	<input type="hidden" name="rut" id="rut" value="{{$dato->rut}}">
				                	<input type="hidden" value="{{ $dato->pers_ncod}}" name="pers_ncod" id="pers_ncod"></div>
				            </div>
				            <div class="col-md-6" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Nombres</strong><span class="requerido">*</span></span>
				                <span class="col-md-9"> <input type="text" id="pers_tnombres" name="pers_tnombres" value="{{ old('pers_tnombres',$dato->pers_tnombres)}}"  class="input-sm form-control filter" data-column-index="2"></span>
				            </div>
				        </div>
						<div class="form-row col-md-12 m-b-10">  
				            <div class="col-md-6" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Apellido Paterno</strong><span class="requerido">*</span></span>
				                <span class="col-md-9"> <input type="text" id="pers_tpaterno" value="{{ old('pers_tpaterno',$dato->pers_tpaterno)}}"  name="pers_tpaterno" class="input-sm form-control filter" data-column-index="1"></span>
				            </div>
				            <div class="col-md-6" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Apellido Materno</strong></span>
				                <span class="col-md-9"> <input type="text" id="pers_tmaterno" value="{{ old('pers_tmaterno',$dato->pers_tmaterno)}}"  name="pers_tmaterno" class="input-sm form-control filter" data-column-index="2"></span>
				            </div>
				        </div>                	
						<div class="form-row col-md-12 m-b-10">  
							<div class="col-md-4" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Fecha de nacimiento</strong>&nbsp;(AAAA-MM-DD)<span class="requerido">*</span></span>
				                <span class="col-md-9"> 
				                	<input type="text" id="pers_fnacimiento" name="pers_fnacimiento" value="{{ old('pers_fnacimiento',$dato->pers_fnacimiento) }}"  class="input-sm form-control filter" data-column-index="1">
				             	</span>
				             </div>
				            <div class="col-md-4" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Teléfono Fijo</strong></span>
				                <span class="col-md-9"> 
				                	<input type="text" id="pers_nfono_fijo" name="pers_nfono_fijo" value="{{ old('pers_nfono_fijo',$dato->pers_nfono_fijo)}}"  class="input-sm form-control filter" data-column-index="1"></span>
				            </div>
				            <div class="col-md-4" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Teléfono Móvil</strong></span>
				                <span class="col-md-9"> 
				                	<input type="text" id="pers_nfono_movil" name="pers_nfono_movil"  value="{{ old('pers_nfono_movil',$dato->pers_nfono_movil)}}"  class="input-sm form-control filter" data-column-index="2"></span>
				            </div>
				        </div>                	
						<div class="form-row col-md-12 m-b-10">  
				            <div class="col-md-6" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Correo Electrónico</strong><span class="requerido">*</span></span>
				                <span class="col-md-9"> 
				                	<input type="text" id="pers_tcorreo" name="pers_tcorreo" value="{{ old('pers_tcorreo',$dato->pers_tcorreo)}}"  class="input-sm form-control filter" data-column-index="1"></span>
				            </div>
				            <div class="col-md-6" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Dirección</strong><span class="requerido">*</span></span>
				                <span class="col-md-9"> 
				                	<input type="text" id="pers_tdireccion" name="pers_tdireccion" value="{{ old('pers_tdireccion',$dato->pers_tdireccion)}}"  class="input-sm form-control filter" data-column-index="2"></span>
				            </div>
				        </div>                	
						<div class="form-row col-md-12 m-b-10">  
				            <div class="col-md-6" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Género</strong><span class="requerido">*</span></span>
				                <span class="col-md-9"> 
				                	<select id="gene_ncod" name="gene_ncod" class="form-control">
				                		<option value="">Seleccione</option>
							            @foreach($generos as $dato_g)
				                		<option value="{{ $dato_g->gene_ncod}}"
				         					@if( $dato->gene_ncod == $dato_g->gene_ncod || old('gene_ncod') ==  $dato_g->gene_ncod ) 
				         						selected 
				         					@endif
				                			>{{ $dato_g->gene_tnombre}}</option>

							            @endforeach
							        </select>
				                </span>
				            </div>
				            <div class="col-md-6" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Previsión</strong><span class="requerido">*</span></span>
				                <span class="col-md-9">
				                	<select id="prev_ncod" name="prev_ncod" class="form-control">
				                		<option value="">Seleccione</option>
							            @foreach($previsiones as $dato_p)
				                		<option value="{{ $dato_p->prev_ncod}}"
				                			@if( $dato->prev_ncod == $dato_p->prev_ncod || old('prev_ncod') ==  $dato_p->prev_ncod) 
				         						selected 
				         					@endif
				                			>{{ $dato_p->prev_tnombre}}</option>
							            @endforeach
							        </select>
				                </span>
				            </div>
				        </div>  
				        <p class="text-right">
				        	<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
				        </p>              	
				    </form>
                </div>
            </div>
        </div>
    </div>  
   	@endforeach
</x-app-layout>
<script type="text/javascript">
$(document).ready(function() {
  jQuery('#pers_fnacimiento').datepicker({
    locale: 'es-es',
    uiLibrary: 'bootstrap4',
    format:'yyyy-mm-dd'
  });
  } );	
</script>