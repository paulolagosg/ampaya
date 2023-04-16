@if(count($horas) > 0)
<p class="text-center">
	Seleccione la hora para el día {{$fecha_formato}}
</p>
<p class="text-center">
@foreach($horas as $h)
	<input type="radio" onclick="$('#hseleccionada').val(this.value)" value="{{$h}}" name="hora">&nbsp;&nbsp;{{$h}}<br />
@endforeach
</p>
<p class="text-center" id="btnReservar">
	<input type="hidden" id="hseleccionada" name="hseleccionada" value="">
	<input type="hidden" id="fseleccionada" name="fseleccionada" value="{{$fecha_formato}}">
	<button class="link-reservar" type="button" onclick="reservar('{{$fecha_formato}}',$('#hseleccionada').val());"  >Reservar</button>
</p>
<p class="col-12" id="divCargar" style="display:none">
	<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
	<span class="sr-only">Cargando...</span>
</p>
@else
<h4 class="text-center text-warning">
	No existen horas disponibles para el día seleccionado, por favor seleccione otro día.
</h4>
@endif