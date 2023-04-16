<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ver Atención
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @foreach($datos_atencion as $atencion)
                <p class="p-4">
                    <a href="{{ route('atenciones.lista')}}">
                        <i class="fa fa-arrow-circle-left"></i>&nbsp;Volver al listado de atenciones
                    </a>
                </p>
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="text-right">
                        <a href="{{ route('documentos.pdf',$id)}}" class="btn btn-primary" target="_blank"><i class="fa fa-file-pdf-o"></i> Generar PDF</a>
                    </p>
                    
                    <p>
                        <h3>
                            <strong>{{ $atencion->nombres }} | {{ $atencion->rut }} | {{ $atencion->edad }} años</strong>
                        </h3>
                    </p>
                    <table id="orderTable" class="table table-striped table-bordered display" style="width:100%">
                        <tr>
                            <th valign="top">Fecha Atención</th>
                            <td valign="top">{!! $atencion->agen_finicio !!}</td>
                        </tr>
                        @if($atencion->pers_tinfo != "")
                        <tr>
                            <th valign="top">Antecedentes</th>
                            <td valign="top">{!! $atencion->pers_tinfo !!}</td>
                        </tr>
                        @endif
                        @if($atencion->aten_tsintomas != "")
                        <tr>
                            <th valign="top">Síntomas</th>
                            <td valign="top">{!! $atencion->aten_tsintomas !!}</td>
                        </tr>
                        @endif
                        @if(count($diagnosticos) > 0 || $atencion->aten_totros != "")
                        <tr>
                            <th valign="top">Diagnóstico(s)</th>
                            <td valign="top">
                                @foreach($diagnosticos as $d)
                                    {!! $d->diag_tdescripcion !!}<br/>
                                @endforeach
                                {!! strtoupper($atencion->aten_totros) !!}
                            </td>
                        </tr>
                        @endif
                        @if(count($laboratorios) > 0 || $atencion->aten_tlabo_otros != "")
                        <tr>
                            <th valign="top">Exámenes de Laboratorio</th>
                            <td valign="top">
                                @foreach($laboratorios as $l)
                                    {!! strtoupper($l->labo_tnombre) !!}<br/>
                                @endforeach
                                {!! $atencion->aten_tlabo_otros !!}
                                @php
                                @endphp
                            </td>
                        </tr>
                        @endif
                        @if(count($imagenes) > 0 || $atencion->aten_tima_otros != "")
                        <tr>
                            <th valign="top">Exámenes de Imagenología</th>
                            <td valign="top">
                                @foreach($imagenes as $i)
                                    {!! strtoupper($i->iman_tnombre) !!}<br/>
                                @endforeach
                                {!! $atencion->aten_tima_otros !!}
                            </td>
                        </tr>
                        @endif
                        @if(count($nucleares) > 0 || $atencion->aten_tnuclear_otros != "")
                        <tr>
                            <th valign="top">Exámenes de Medicina Nuclear</th>
                            <td valign="top">
                                @foreach($nucleares as $n)
                                    {!! strtoupper($n->nucl_tnombre) !!}<br/>
                                @endforeach
                                {!! $atencion->aten_tnuclear_otros !!}
                            </td>
                        </tr>
                        @endif
                        @if($atencion->aten_tfarmacos != "")
                        <tr>
                            <th valign="top">Fármacos</th>
                            <td valign="top">
                                {!! strtoupper($atencion->aten_tfarmacos) !!}<br/>
                            </td>
                        </tr>
                        @endif
                        @if($atencion->aten_tindicaciones != "")
                        <tr>
                            <th valign="top">Otras indicaciones</th>
                            <td valign="top">
                                {!! strtoupper($atencion->aten_tindicaciones) !!}<br/>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>