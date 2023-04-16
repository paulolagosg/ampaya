<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agenda > Bloquear Agenda
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  shadow-sm sm:rounded-lg">
            	{{-- <p class="p-4">
            		<a href="{{ route('agenda') }}">
            			<i class="fa fa-arrow-circle-left"></i>&nbsp;Volver a la agenda
            		</a>
            	</p> --}}
                <div class="p-6 bg-white border-b border-gray-200">
                	<p class="text-right">
                		<button class="btn btn-primary" onclick="window.location.href='{{ route('agenda.bloqueos')}}'"><i class="fa fa-plus-circle"></i>&nbsp;Agregar</button>
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
								<th class="text-center">Médico</th>
								<th class="text-center">Fecha Inicio</th>
								<th class="text-center">Fecha Térmimo</th>
								<th class="text-center">Estado</th>
								<th class="text-center">Acciones</th>
								</tr>
							</thead>
							<tbody>
							@foreach($bloqueos as $dato)
								<tr>
									<td>{{ $dato->medico}}</td>
									<td class="text-center">{{ $dato->agbl_finicio}}</td>
									<td class="text-center">{{ $dato->agbl_ftermino}}</td>
									<td class="text-center">{{ $dato->estado}}</td>
									<td class="text-center">
{{-- 										@if($dato->agbl_nestado == 1)
 --}}										<a href="{{ route('bloqueos.eliminar',$dato->agbl_ncod)}}"><i class="fa fa-trash" title="Eliminar"></i></a>
										{{-- @else
											<a href="{{ route('personas.activar',$dato->agbl_nestado)}}"><i class="fa fa-check-square-o" title="Activar"></i></a>
										@endif --}}
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