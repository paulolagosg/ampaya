<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Informe
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <p class="p-4">
                    <a href="{{ route('informes.lista')}}">
                        <i class="fa fa-arrow-circle-left"></i>&nbsp;Volver al listado de informes
                    </a>
                </p>
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    @foreach($datos_informe as $di)
                    <form  id="fDoctos" method="POST" action="{{ route('informes.modificar') }}">
                        @csrf
                        <div class="form-row col-md-12 m-b-10">  
                            <div class="col-md-4" style="display: table;">
                                <span class="col-md-3 text-bold"><strong>Paciente</strong><span class="requerido">*</span></span>
                                <span class="col-md-9"> 
                                    <select id="pers_nrut" name="pers_nrut" class="form-control select2" searchable="Buscar..." style="width:100% !important">
                                        <option value="" disabled selected>Seleccione</option>
                                        @foreach($pacientes as $dato_p)
                                            <option value="{{ $dato_p->pers_nrut}}" 
                                                @if(old('pers_nrut') == $dato_p->pers_nrut || $dato_p->pers_nrut == $di->pers_nrut) 
                                                selected 
                                                @endif >{{ $dato_p->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="pein_ncod" id="pein_ncod" value="{{$id}}">
                                </span>
                            </div>
                            <div class="col-md-3" style="display: table;">
                                <span class="col-md-3 text-bold"><strong>Tipo Informe</strong></span>
                                <span class="col-md-9">
                                    <select id="tiin_ncod" name="tiin_ncod" class="form-control">
                                        <option value="">Seleccione</option>
                                        @foreach($tipos_informes as $dato_a)
                                        <option value="{{ $dato_a->tiin_ncod}}"
                                            @if(old('tiin_ncod') == $dato_a->tiin_ncod || $di->tiin_ncod == $dato_a->tiin_ncod) selected @endif
                                            >{{ $dato_a->tiin_tnombre}}</option>
                                        @endforeach
                                    </select>
                                </span>
                            </div>
                            <div class="col-md-5" style="display: table;">
                                <span class="col-md-3 text-bold"><strong>Nombre</strong></span><span class="requerido">*</span>
                                <span class="col-md-9">
                                    <input type="text" class="form-control" name="pein_tnombre" id="pein_tnombre" value="{{ old('pein_tnombre',$di->pein_tnombre)}}">
                                </span>
                            </div>
                        </div>                              
                        <div class="form-row col-md-12 m-b-10">  
                            <div class="col-md-12" style="display: table;">
                                <span class="col-md-3 text-bold"><strong>Informe</strong></span><span class="requerido">*</span>
                                <span class="col-md-9">
                                    <textarea id="pein_tinforme" name="pein_tinforme" class="form-control">{{ old('pein_tinforme',$di->pein_tinforme)}}</textarea>
                                </span>
                            </div>
                        </div>
                        <p class="text-right">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                        </p>                
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.tiny.cloud/1/779rqxm1bq47n7i0pgujhz6l5821gsc2kf4s0q717wrvtyde/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script type="text/javascript">
    function initMCEexact(e){
    tinyMCE.init({
        selector : 'textarea#'+e,
        menu: {
            file: { title: 'File', items: ' ' },
            edit: { title: 'Edicion', items: 'undo redo | cut copy paste | selectall | searchreplace' },
            view: { title: 'View', items: ' ' },
            insert: { title: 'Insertar', items: ' ' },
            format: { title: 'Formato', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align lineheight | forecolor backcolor | removeformat' },
            tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount' },
            table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' },
            help: { title: 'Help', items: 'help' }
        }
        });
    }
    $(document).ready(function() {
        $('.select2').select2();
        initMCEexact("pein_tinforme");
    });
</script>