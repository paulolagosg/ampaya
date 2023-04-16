<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Documentos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <p class="p-4">
                    <a href="{{ route('documentos.lista')}}">
                        <i class="fa fa-arrow-circle-left"></i>&nbsp;Volver al listado de documentos
                    </a>
                </p>
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    <form  id="fDoctos" method="POST" action="{{ route('documentos.agregar') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row col-md-12 m-b-10">  
                            <div class="col-md-6" style="display: table;">
                                <span class="col-md-3 text-bold"><strong>Paciente</strong><span class="requerido">*</span></span>
                                <span class="col-md-9"> 
                                    <select id="pers_nrut" name="pers_nrut" class="form-control select2" searchable="Buscar..." style="width:100% !important">
                                        <option value="" disabled selected>Seleccione</option>
                                        @foreach($pacientes as $dato_p)
                                            <option value="{{ $dato_p->pers_nrut}}" @if(old('pers_nrut') == $dato_p->pers_nrut ) selected @endif >{{ $dato_p->nombre }}</option>
                                        @endforeach
                                    </select>
                                </span>
                            </div>
                            <div class="col-md-3" style="display: table;">
                                <span class="col-md-3 text-bold"><strong>Tipo Documento</strong></span>
                                <span class="col-md-9">
                                    <select id="tido_ncod" name="tido_ncod" class="form-control">
                                        <option value="">Seleccione</option>
                                        @foreach($tipos_documentos as $dato_a)
                                        <option value="{{ $dato_a->tido_ncod}}"
                                            @if(old('tido_ncod') == $dato_a->tido_ncod ) selected @endif
                                            >{{ $dato_a->tido_tnombre}}</option>
                                        @endforeach
                                    </select>
                                </span>
                            </div>
                        </div>                              
                        <div class="form-row col-md-12 m-b-10">  
                            <div class="col-md-6" style="display: table;">
                                <p>
                                    <input id="documentos" name="documentos[]" type="file" class="form-control"  data-show-upload="true" data-show-caption="true" multiple>
                                </p>
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
<link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>