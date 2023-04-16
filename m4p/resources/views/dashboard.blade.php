<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inicio
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
                    <div class="table-responsive">
                        <p>
                            <i class="fa fa-circle" style="color:#ea9a04"></i>&nbsp;Reservada
                            <i class="fa fa-circle" style="color:#2bae07"></i>&nbsp;Confirmada&nbsp;
                            <i class="fa fa-circle" style="color:#0737ae"></i>&nbsp;Presentado/a&nbsp;
                            <i class="fa fa-circle" style="color:#ff0000"></i>&nbsp;Cancelada&nbsp;
                            <i class="fa fa-circle" style="color:#f9f911"></i>&nbsp;No se presenta
                        </p>
                        <table id="orderTable" class="table table-striped table-bordered display" style="width:100%">
                            <thead>
                                <tr>
                                @if($nTipoUsuario != 2)
                                <th class="text-center">Médico</th>
                                @endif
                                <th class="text-center">RUT/DNI</th>
                                <th class="text-center">Paciente</th>
                                <th class="text-center">Tipo Paciente</th>
                                <th class="text-center">Información adicional</th>
                                <th class="text-center">Fecha Cita</th>
                                <th class="text-center" colspan="2">Estado</th>
                                <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($agenda as $dato)
                                <tr>
                                    @if($nTipoUsuario != 2)
                                    <td>{{ $dato->medico}}</td>
                                    @endif
                                    <td>{{ $dato->rut}}</td>
                                    <td>{{ $dato->paciente}}</td>
                                    <td>{{ $dato->tipa_tnombre}}</td>
                                    <td>{{ $dato->pers_tnotas}}</td>
                                    <td class="text-center">{{ $dato->agen_finicio  }}</td>
                                    <td class="text-center">
                                        <select id="esag_ncod" name="esag_ncod" class="form-control" onchange="actualizarEstado(this.value,{{$dato->agen_ncod}})">
                                            @foreach($estados as $dato_e)
                                            <option value="{{ $dato_e->esag_ncod}}"
                                                @if(old('esag_ncod') == $dato_e->esag_ncod || $dato_e->esag_ncod == $dato->esag_ncod ) selected @endif
                                                >{{ $dato_e->esag_tnombre}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center"><i class="fa fa-circle" style="color: {{ $dato->color }};"> </i></td>
                                    <td class="text-center">
                                        <a href="{{ route('agenda.editar',$dato->agen_ncod)}}"><i class="fa fa-edit" title="Modificar"></i></a>&nbsp;
                                        @if($nTipoUsuario == 2 && ($dato->esag_ncod == 2 || $dato->esag_ncod == 3) )
                                        	<a href="{{ route('atenciones.atender',$dato->agen_ncod)}}"><i class="fa fa-user-md" title="Atender"></i></a>
                                        	
                                        @endif
                                        @if($dato->aten_ncod > 0)
                                        	<a href="{{ route('documentos.receta',$dato->aten_ncod) }}" target="_blank"><i class="fa fa-file-text" title="Ver receta"></i></a>&nbsp;
                                        	
                                        	<a href="{{ route('documentos.enviar',$dato->aten_ncod) }}"><i class="fa fa fa-send" title="Enviar receta"></i></a>
                                        @endif
                                        &nbsp;
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
