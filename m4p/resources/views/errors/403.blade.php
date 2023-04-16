<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  shadow-sm sm:rounded-lg">
            	<p class="p-4">
            		<a href="{{ route('dashboard') }}">
            			<i class="fa fa-arrow-circle-left"></i>&nbsp;Volver al inicio
            		</a>
            	</p>
                <div class="p-6 bg-white border-b border-gray-200">
                	<p class="text-center">
                		<img src="{{ asset('img/logo.svg') }}" style="margin:auto">
                		Ud. no tiene acceso a este contenido.
                	</p>
                </div>
            </div>
        </div>
    </div>  
</x-guest-layout>
