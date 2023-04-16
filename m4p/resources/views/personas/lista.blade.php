<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pacientes > Administrar Pacientes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                	<p class="text-right">
                		<button class="btn btn-primary" onclick="window.location.href='{{ route('personas.crear')}}'"><i class="fa fa-plus-circle"></i>&nbsp;Agregar</button>
                	</p>
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
								<th class="text-center">RUT/DNI</th>
								<th class="text-center">Nombre</th>
								<th class="text-center">Teléfono(s)</th>
								<th class="text-center">Correo Electrónico</th>
								<th class="text-center">Estado</th>
								<th class="text-center">Acciones</th>
								</tr>
							</thead>
							<tbody>
							@foreach($personas as $dato)
								<tr>
									<td class="text-right">{{ $dato->rut}}</td>
									<td>{{ $dato->nombres}}</td>
									<td class="text-center">{{ $dato->fono}}</td>
									<td>{{ $dato->pers_tcorreo}}</td>
									<td class="text-center">{{ $dato->estado}}</td>
									<td class="text-center"><a href="{{ route('personas.editar',$dato->pers_ncod)}}"><i class="fa fa-edit" title="Modificar"></i></a>&nbsp;
										@if($dato->pers_nestado == 1)
										<a href="{{ route('personas.eliminar',$dato->pers_ncod)}}"><i class="fa fa-trash" title="Eliminar"></i></a>
										@else
											<a href="{{ route('personas.activar',$dato->pers_ncod)}}"><i class="fa fa-check-square-o" title="Activar"></i></a>
										@endif
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