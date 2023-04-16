<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pacientes > Informes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="text-right">
                        <button class="btn btn-primary" onclick="window.location.href='{{ route('informes.crear')}}'"><i class="fa fa-plus-circle"></i>&nbsp;Agregar</button>
                    </p>
                    @if(session()->has('message'))
                      <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                          {{ session()->get('message') }}
                      </div>
                    @endif
                    <table id="orderTable" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                            <th class="text-center">RUT/DNI</th>
                            <th class="text-center">Paciente</th>
                            <th class="text-center">Informe</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($informes as $docto)
                            <tr>
                                <td class="text-right">{{ $docto->rut }}</td>
                                <td>{{ $docto->paciente }}</td>
                                <td>{{ $docto->pein_tnombre }}</td>
                                <td>{{ $docto->tiin_tnombre }}</td>
                                <td class="text-center">{{ $docto->pein_finforme }}</td>
                                <td class="text-center">
                                    <a href="{{ route('informes.editar',$docto->pein_ncod)}}"><i class="fa fa-edit" title="Editar"></i></a>&nbsp;
                                    <a target="_blank" href="{{ route('informes.ver',$docto->pein_ncod)}}"><i class="fa fa-eye" title="Ver"></i></a>&nbsp;
                                    <a href="{{ route('informes.enviar',$docto->pein_ncod)}}"><i class="ffa fa fa-send" title="Enviar informe"></i></a>&nbsp;
                                    <a href="{{ route('informes.eliminar',$docto->pein_ncod) }}"><i class="fa fa-trash" title="Eliminar"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>