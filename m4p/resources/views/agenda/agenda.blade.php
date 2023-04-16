
<x-app-layout>
<script>
  document.addEventListener('DOMContentLoaded', function() {

    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek,dayGridDay'
      },
      locale: 'es',
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      @if(count($medicos) > 0 )
        slotDuration: '00:{{ $medicos[0]->user_nminutos }}:00',
      @else
        slotDuration: '00:30:00',
      @endif
      dayMaxEvents: true, // allow "more" link when too many events
      @if($nTipoUsuario == 2 && count($medicos) > 0 )
        events: '{{ route("agenda.datos",$medicos[0]->pers_nrut) }}'
      @else
        events: '{{ route("agenda.datos",0) }}'
      @endif
    });

    calendar.render();

  });
  </script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agenda > Ver Agenda
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              @if(session()->has('message'))
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                      {{ session()->get('message') }}
                  </div>
              @endif
                <div class="p-6 bg-white border-b border-gray-200">
                   @if($nTipoUsuario != 2)
                    <div class="form-row col-md-12 m-b-10" >
                      <div class="col-md-2 text-bold">
                          <strong>Médico</strong>
                      </div>
                      <div class="col-md-10">
                       
                        <select id="pers_nrut_medico" name="pers_nrut_medico" class="form-control" onchange="agendaMedico(this.value)">
                          <option value="">Seleccione</option>
                          @foreach($medicos as $dato_m)
                          <option value="{{ $dato_m->pers_nrut}}-{{$dato_m->user_nminutos}}"
                          @if(old('pers_nrut_medico') == $dato_m->pers_nrut ) selected @endif
                          >{{ $dato_m->nombre}}</option>
                          @endforeach
                        </select>
                        
                      </div>
                    </div>
                    @endif
                    <p>&nbsp;</p>
                    <p>
                        <i class="fa fa-circle" style="color:#ea9a04"></i>&nbsp;Reservada
                        <i class="fa fa-circle" style="color:#2bae07"></i>&nbsp;Confirmada&nbsp;
                        <i class="fa fa-circle" style="color:#0737ae"></i>&nbsp;Presentado/a&nbsp;
                        <i class="fa fa-circle" style="color:#ff0000"></i>&nbsp;Cancelada&nbsp;
                        <i class="fa fa-circle" style="color:#f9f911"></i>&nbsp;No se presenta
                    </p>
                    <p>&nbsp;</p>

                    <div id="calendar"></div>
                  </div>
            </div>
        </div>
    </div>
</x-app-layout>
