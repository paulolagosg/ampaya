<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Atenciones
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
                        <table id="orderTable" class="table table-striped table-bordered display" style="width:100%">
                            <thead>
                                <tr>
                                @if($nTipoUsuario != 2)
                                <th class="text-center">Médico</th>
                                @endif
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
                                    @if($nTipoUsuario != 2)
                                    <td>{{ $dato->medico}}</td>
                                    @endif
                                    <td class="text-right">{{ $dato->rut}}</td>
                                    <td>{{ $dato->nombre}}</td>
                                    <td class="text-center">{{ $dato->aten_ffecha  }}</td>
                                    <td class="text-center">
                                        {{ $dato->esat_tnombre }}
                                    </td>
                                    <td class="text-center">
                                        @if(Auth::user()->tius_ncod == 2)
                                        <a href="{{ route('atenciones.ver',$dato->aten_ncod) }}"><i class="fa fa-eye" title="Ver atención"></i></a>&nbsp;
                                        @endif
                                        <a href="{{ route('documentos.receta',$dato->aten_ncod) }}" target="_blank"><i class="fa fa-file-text" title="Ver receta"></i></a>
                                        &nbsp;
                                        <a href="{{ route('documentos.enviar',$dato->aten_ncod) }}"><i class="fa fa fa-send" title="Enviar receta"></i></a>
                                        
                                        
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
