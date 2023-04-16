<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agenda > {{ __('Block Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <p class="p-4">
                    <a href="{{ route('bloqueos.lista') }}">
                        <i class="fa fa-arrow-circle-left"></i>&nbsp;Volver al listado de bloqueos
                    </a>
                </p>
                @if(session()->has('message'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                            {{ session()->get('message') }}
                        </div>
                    @endif
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    <form  id="fPacientes" method="POST" action="{{ route('agenda.bloquear') }}">
                        @csrf
                        <div class="form-row col-md-12 m-b-10">  
                            <div class="col-md-6" style="display: table;">
                                <span class="col-md-3 text-bold"><strong>Médico</strong><span class="requerido">*</span></span>
                                <span class="col-md-9">
                                    <select id="pers_nrut_medico" name="pers_nrut_medico" class="form-control">
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
                                <span class="col-md-3 text-bold"><strong>Fecha Inicio</strong><span class="requerido">*</span></span>
                                <span class="col-md-9"> <input type="text" id="agbl_finicio" name="agbl_finicio" value="{{ old('agbl_finicio')}}" class="input-sm form-control filter" data-column-index="1" >
                                    
                                    </span>
                            </div>
                            <div class="col-md-3" style="display: table;">
                                <span class="col-md-3 text-bold"><strong>Fecha Término</strong><span class="requerido">*</span></span>
                                <span class="col-md-9"> <input type="text" id="agbl_ftermino" name="agbl_ftermino" value="{{ old('agbl_ftermino')}}"  class="input-sm form-control filter" data-column-index="2"></span>
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
<script type="text/javascript">
$(document).ready(function() {
    $('#agbl_finicio').datepicker({
        locale: 'es-es',
        uiLibrary: 'bootstrap4',
        format:'yyyy-mm-dd',
        disableDaysOfWeek: [0, 6],
        weekStartDay: 1
        //disableDates:datesForDisable
    });
    $('#agbl_ftermino').datepicker({
        locale: 'es-es',
        uiLibrary: 'bootstrap4',
        format:'yyyy-mm-dd',
        disableDaysOfWeek: [0, 6],
        weekStartDay: 1
        //disableDates:datesForDisable
    });
}); 
</script>