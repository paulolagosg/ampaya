<x-guest-layout>
@if($medicos[0]->ceme_ncod == 1)
	<link rel="stylesheet" href="/css/reservaEmed.css" crossorigin="anonymous">
		<div id="top-header">
			<div class="container clearfix">
				<div id="et-info">
					<span id="et-info-phone"><i class="fa fa-phone"></i>&nbsp;+56 932351520</span>
						<a href="mailto:emed.trizano@gmail.com"><span id="et-info-email"><i class="fa fa-envelope"></i>&nbsp;emed.trizano@gmail.com</span></a>
				</div>
				<div id="et-secondary-menu">
				</div>
			</div>
	 	 </div>
		<header id="main-header" data-height-onload="66" data-height-loaded="true" data-fixed-height-onload="66" style="top: 31px;">
			<div class="container clearfix et_menu_container">
				<div class="logo_container">
					<span class="logo_helper"></span>
					<a href="https://emedtrizano.cl/">
						<img src="/img/emed.png" style="width:145px;" alt="Emed Trizano" id="logo" data-height-percentage="90" data-actual-width="1727" data-actual-height="628.234">
					</a>
				</div>
				<div id="et-top-navigation" data-height="51" data-fixed-height="40" style="padding-left: 193px;">
					<div id="et_mobile_nav_menu">
						<div class="mobile_nav closed">
						</div>
					</div>				
				</div> 
			</div>
		</header>

@else
	<link rel="stylesheet" href="/css/reservaPb.css" rossorigin="anonymous">
	
@endif
	<main>
	
		<div class="p-5" style="background-color:#f1f5f7 !important;">
			<h1><i class="fa fa-clock-o"></i>&nbsp;Reserva de Horas</h1>
			<x-auth-validation-errors class="mb-4" :errors="$errors" />
			@if(session()->has('message'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert">×</button>	
					{{ session()->get('message') }} 
				</div>
			@endif
			<form id="fReservas" method="POST" action="{{ route('agenda.reservar')}}" >
			@csrf
			<div class="form-row col-md-12 m-2 p-2"> 
				<div class="col-md-3">
					<div class="encabezado-div">
						1. Búsqueda 
					</div>
					<div class="cuerpo-div">
						<select id="pers_nrut_medico" name="pers_nrut_medico" class="form-control select2" searchable="Buscar..." style="width:100% !important" onchange="mostrarMedico(this.value);">
							<option value="" disabled selected>Seleccione</option>
							@foreach($medicos as $m)
					            <option value="{{ $m->pers_nrut}}" @if(old('pers_nrut_medico') == $m->pers_nrut || $m->pers_nrut_medico == $m->pers_nrut) selected @endif >{{ $m->nombre }}</option>
					        @endforeach
						</select>
						@foreach($medicos as $m)
							<div class="form-row col-md-12 m-2 p-2 infoMedico" id="medico{{$m->pers_nrut}}" style="display:none;">  
								<div class="col-md-12">
									<img src="{{$m->user_tfoto}}" class="foto" />
								</div>
								<div class="col-md-12">
									<span>
										<h2>
										@if($m->gene_ncod == 1)
											Dra.
										@else
											Dr.
										@endif
										{{$m->nombre}}
										</h2>
										<h3>{!! $m->espe_tnombre !!}</h3>
									</span>
									<!-- p class="info">{{$m->pers_tinfo}}</p -->
								</div>
							</div>
						@endforeach	
					</div>
				</div>
				<div class="col-md-3">
					<div class="encabezado-div">
						2. Datos del Paciente 
					</div>
					<div class="cuerpo-div">
						<p class="text-left"><label class="requerido">*&nbsp;</label>Requerido.<br/><label class="text-warning">*&nbsp;</label>Al menos uno.</p>
						<div class="form-row col-md-12 p-1">
						<label class="small font-weight-bolder">Tipo Documento</label><span class="requerido">&nbsp;*</span>	
							<select id="pers_ntipo_docto" name="pers_ntipo_docto" class="form-control filter">
				                <option value="">Tipo Documento</option>
				                <option value="1"
				                @if(old('pers_ntipo_docto') ==  1 ) 
				         				selected 
				         			@endif
				         		>RUT</option>
				                <option value="2" 
				                @if(old('pers_ntipo_docto') ==  2 ) 
				         				selected 
				         			@endif>DNI/Pasaporte</option>
							</select>		
						</div>
						<div class="form-row col-md-12 p-1">
						<input type="hidden" name="ceme_ncod" id="ceme_ncod" value="{{$medicos[0]->ceme_ncod}}">
						<label class="small font-weight-bolder">RUT/DNI/Pasaporte</label><span class="requerido">&nbsp;*</span>
							<input onblur="datosPaciente(this.value,{{$medicos[0]->ceme_ncod}})" type="text" id="pers_nrut_paciente" name="pers_nrut_paciente" value="{{ old('pers_nrut_paciente')}}" class="input-sm form-control filter" data-column-index="1" placeholder="RUT/DNI/Pasaporte" >
							<i id="cargando" style="display:none" class="fa fa-spinner fa-spin fa-2x fa-fw"></i>
						</div>
						<div class="form-row col-md-12 p-1">
						<label class="small font-weight-bolder">Nombres</label><span class="requerido">&nbsp;*</span>
							<input type="text" id="pers_tnombres" name="pers_tnombres" value="{{ old('pers_tnombres')}}" class="input-sm form-control filter" data-column-index="1" placeholder="Nombres" >
						</div>
						<div class="form-row col-md-12 p-1">
						<label class="small font-weight-bolder">Apellido Paterno</label><span class="requerido">&nbsp;*</span>
							<input type="text" id="pers_tpaterno" name="pers_tpaterno" value="{{ old('pers_tpaterno')}}" class="input-sm form-control filter" data-column-index="1" placeholder="Apellido Paterno" >
						</div>
						<div class="form-row col-md-12 p-1">
						<label class="small font-weight-bolder">Apellido Materno</label>
							<input type="text" id="pers_tmaterno" name="pers_tmaterno" value="{{ old('pers_tmaterno')}}" class="input-sm form-control filter" data-column-index="1" placeholder="Apellido Materno" >
						</div>
						<div class="form-row col-md-12 p-1">
						<label class="small font-weight-bolder">Fecha de Nacimiento</label><span class="requerido">&nbsp;*</span>
							<input type="text" id="pers_fnacimiento" name="pers_fnacimiento" value="{{ old('pers_fnacimiento')}}" class="input-sm form-control filter" data-column-index="1" placeholder="(dd/mm/yyyy)" >
						</div>
						<div class="form-row col-md-12 p-1">
						<label class="small font-weight-bolder">Teléfono Fijo</label><span class="text-warning">&nbsp;*</span>
							<input type="text" id="pers_nfono_fijo" name="pers_nfono_fijo" value="{{ old('pers_nfono_fijo')}}" class="input-sm form-control filter" data-column-index="1" placeholder="Teléfono Fijo" >
						</div>
						<div class="form-row col-md-12 p-1">
						<label class="small font-weight-bolder">Teléfono Móvil</label><span class="text-warning">&nbsp;*</span>
							<input type="text" id="pers_nfono_movil" name="pers_nfono_movil" value="{{ old('pers_nfono_movil')}}" class="input-sm form-control filter" data-column-index="1" placeholder="Teléfono Móvil" >
						</div>						
						<div class="form-row col-md-12 p-1">
						<label class="small font-weight-bolder">Correo Electrónico</label><span class="requerido">&nbsp;*</span>
							<input type="text" id="pers_tcorreo" name="pers_tcorreo" value="{{ old('pers_tcorreo')}}" class="input-sm form-control filter" data-column-index="1" placeholder="Correo Electrónico" >
						</div>						
						<div class="form-row col-md-12 p-1">
						<label class="small font-weight-bolder">Dirección</label><span class="requerido">&nbsp;*</span>
							<input type="text" id="pers_tdireccion" name="pers_tdireccion" value="{{ old('pers_tdireccion')}}" class="input-sm form-control filter" data-column-index="1" placeholder="Dirección" >
						</div>						
						<div class="form-row col-md-12 p-1">
						<label class="small font-weight-bolder">Género</label>
							<select id="gene_ncod" name="gene_ncod" class="form-control filter">
				                <option value="">Género</option>
							    @foreach($generos as $dato_g)
				                <option value="{{ $dato_g->gene_ncod}}"
				         			@if(old('gene_ncod') ==  $dato_g->gene_ncod ) 
				         				selected 
				         			@endif
				                	>{{ $dato_g->gene_tnombre}}</option>
							    @endforeach
							</select>
						</div>						
						<div class="form-row col-md-12 p-1">
						<label class="small font-weight-bolder">Previsión de Salud</label><span class="requerido">&nbsp;*</span>
							<select id="prev_ncod" name="prev_ncod" class="form-control filter">
				                <option value="">Previsión</option>
							    @foreach($previsiones as $dato_p)
				                <option value="{{ $dato_p->prev_ncod}}"
				                	@if(old('prev_ncod') ==  $dato_p->prev_ncod) 
				         				selected 
				         			@endif
				                	>{{ $dato_p->prev_tnombre}}</option>
							    @endforeach
							</select>
						</div>
						<div class="form-row col-md-12 p-1" style="display:none;text-align:left !important;color:#dc2626" id="mensajes">
						</div>
						<div class="form-row col-md-12 p-1">
							<button id="btnBuscar" style="display:none;" class="boton-activo" type="button" onclick="validaReserva();">Buscar</button>
						</div>						
					</div>
				</div>
				<div class="col-md-6">
					<div class="encabezado-div">
						3. Fecha/Hora 
					</div>
					<div class="cuerpo-div form-row">
					@foreach($medicos as $m)
						<div class="col-8">
							
								<script>
									document.addEventListener('DOMContentLoaded', function() {
										var today = new Date();
										var dd = String(today.getDate()).padStart(2, '0');
										var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
										var yyyy = today.getFullYear();
										today = yyyy + '-' + mm + '-' + dd;
				    					
				    					var calendarEl = document.getElementById('calendar{{$m->pers_nrut}}');
				    					var calendar = new FullCalendar.Calendar(calendarEl, {
      										headerToolbar: {
				    	    					left: 'prev,next today',
					        					center: 'title',
					        					right: ''
				      						},
				      						locale: 'es',
      										navLinks: false, // can click day/week names to navigate views
      										editable: true,
      										slotDuration: '00:{{$m->user_nminutos}}:00',
      										dayMaxEvents: true, // allow "more" link when too many events
      										contentHeight: 300,
      										hiddenDays: [ {{$m->user_tdias}} ],
      										selectable:true,
  											dateClick: function(info) {
  												$('#horas{{$m->pers_nrut}}').html('<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>');
        										$('#horas{{$m->pers_nrut}}').load("/muestra_horas/{{$m->pers_nrut}}/"+info.dateStr);
        										$('#horas{{$m->pers_nrut}}').show();
    						
  											},
  											validRange: {
    											start: today
  											}
    									});
    									calendar.render();
			  						});
								</script>
								<div id="calendar{{$m->pers_nrut}}" class="divCalendario" style="display:block"></div>
							
						</div>
						<div class="col-4 divHora" id="horas{{$m->pers_nrut}}" style="display:none">
							<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
							<span class="sr-only">Cargando...</span>
						</div>
						@endforeach
					</div>
				</div>
			</div> 
			</form>
		</div>
	</main>
</x-guest-layout>



<link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    	$('.select2').select2();
    	ocultaCalendario();
	});	
	
</script>
