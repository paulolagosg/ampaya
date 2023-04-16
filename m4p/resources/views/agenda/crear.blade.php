<x-app-layout>	
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agenda > {{ __('Add Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  shadow-sm sm:rounded-lg">
                <p class="p-4">
            		<a href="{{ route('agenda') }}">
            			<i class="fa fa-arrow-circle-left"></i>&nbsp;Volver a la agenda
            		</a>
            	</p>
            	<div class="p-6 bg-white border-b border-gray-200">
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                	<form  id="fPacientes" method="POST" action="{{ route('agenda.agregar') }}">
                		@csrf
                		<div class="form-row col-md-12 m-b-10">  
				            <div class="col-md-3" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Médico</strong><span class="requerido">*</span></span>
				                <span class="col-md-9">
				                	<select id="pers_nrut_medico" name="pers_nrut_medico" class="form-control"  onchange="bloqueaDiasMedico(this.value);traeHorasMedico(this.value,1)">
				                		<option value="">Seleccione</option>
							            @foreach($medicos as $dato_m)
				                		<option value="{{ $dato_m->pers_nrut}}"
				                			@if(old('pers_nrut_medico') == $dato_m->pers_nrut ) selected @endif
				                			>{{ $dato_m->nombre}}</option>
							            @endforeach
							        </select>
				                </span>
				            </div>
				            <div class="col-md-3" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Fecha</strong><span class="requerido">*</span></span>
				                <span class="col-md-9"> 
				                	<input type="text" id="agen_finicio" name="agen_finicio" value="{{ old('agen_finicio')}}" class="input-sm form-control filter" data-column-index="1" >
				                </span>
				            </div>
				            <div class="col-md-3" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Hora</strong><span class="requerido">*</span></span>
				                <span class="col-md-9">
				                	<select id="agen_hinicio" name="agen_hinicio"  class="form-control"><option value="">Seleccione</option></select>
				                	<!--input type="text" id="agen_hinicio" name="agen_hinicio" value="{{ old('agen_hinicio')}}"  class="input-sm form-control filter" data-column-index="2"--></span>
				            </div>
				            <div class="col-md-3" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Tipo paciente</strong></span>
				                <span class="col-md-9">
				                	<select id="tipa_ncod" name="tipa_ncod" class="form-control">
				                		<option value="">Seleccione</option>
							            @foreach($tipos_pacientes as $dato_p)
				                		<option value="{{ $dato_p->tipa_ncod}}"
				                			@if(old('tipa_ncod') == $dato_p->tipa_ncod) selected @endif
				                			>{{ $dato_p->tipa_tnombre}}</option>
							            @endforeach
							        </select>
				                </span>
				            </div>
				        </div>
						<div class="form-row col-md-12 m-b-10">  
				            <div class="col-md-6" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Paciente</strong><span class="requerido">*</span></span>
				                <span class="col-md-9"> 
				                	<select id="pers_nrut_paciente" name="pers_nrut_paciente" class="form-control select2" searchable="Buscar..." style="width:100% !important">
									    <option value="" disabled selected>Seleccione</option>
									    @foreach($pacientes as $dato_p)
					                		<option value="{{ $dato_p->pers_nrut}}" @if(old('pers_nrut_paciente') == $dato_p->pers_nrut ) selected @endif >{{ $dato_p->nombre }}</option>
					                	@endforeach
									</select>
				                </span>
				            </div>
				            <div class="col-md-3" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Tipo Atención</strong></span>
				                <span class="col-md-9">
				                	<select id="tiat_ncod" name="tiat_ncod" class="form-control">
				                		<option value="">Seleccione</option>
							            @foreach($atenciones as $dato_a)
				                		<option value="{{ $dato_a->tiat_ncod}}"
				                			@if(old('tiat_ncod') == $dato_a->tiat_ncod ) selected @endif
				                			>{{ $dato_a->tiat_tnombre}}</option>
							            @endforeach
							        </select>
				                </span>
				            </div>
				            <div class="col-md-3" style="display: table;">
				                <div class="col-md-9 text-bold"><strong>¿Es sobrecupo?</strong>&nbsp;</div>
				                <div class="col-md-3"> 
				                	<input type="checkbox" id="agen_nsobrecupo" name="agen_nsobrecupo" value="1">
				             	</div>
				             </div>
				        </div>                	         	
						<div class="form-row col-md-12 m-b-10">  
				            <div class="col-md-6" style="display: table;">
				                <span class="col-md-3 text-bold"><strong>Nota adicional</strong></span>
				                <span class="col-md-9"> 
				                	<textarea id="pers_tnota" name="pers_tnota" class="form-control"></textarea>
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
</x-app-layout>
<link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	traeHorasMedico($('#pers_nrut_medico').val(),1,'{{old('agen_hinicio')}}');
	var datesForDisable = [];//["2022-05-09","2022-05-10","2022-05-11","2022-05-12","2022-05-13"]
	var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
  	//$('#agen_hinicio').timepicker({
    //	locale: 'es-es',
   // 	uiLibrary: 'bootstrap4'

    //});
    $('#agen_finicio').datepicker({
    	locale: 'es-es',
    	uiLibrary: 'bootstrap4',
    	format:'yyyy-mm-dd',
    	disableDaysOfWeek: [],
    	weekStartDay: 1,
    	disableDates:datesForDisable,
    	minDate: today,
    	iconsLibrary: 'fontawesome',
    });
    $('.select2').select2();
});	
</script>