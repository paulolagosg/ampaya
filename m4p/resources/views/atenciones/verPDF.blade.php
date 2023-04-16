<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<style>
    .page-break {
        page-break-after: always;
    }
</style>


<div class="p-6 bg-white border-b border-gray-200">
    <table style="width:100%">
    @foreach($centro_medico as $cm)
        <tr>
            <td valign="top" class="text-center" style="width:50%">
                <label style="font-size: 20px"><strong>{{ $cm->ceme_tnombre }}</strong></label><br/>
                <label style="font-size: 12px">
                    {!! $cm->ceme_tdireccion !!}<br/>
                    {!! $cm->ceme_tcorreo !!}<br/>
                    +56{{ $cm->ceme_nfono_movil }}<br/>
                </label>
            </td>
            <td valign="top" class="text-center" style="width:50%"><img src="{{url('/')}}/img/{{$cm->ceme_tlogo}}" style="width:70%"></td>
        </tr>
    @endforeach
    </table>
    
    @foreach($datos_atencion as $atencion)
    <p>
        <h5>
            <strong>{{ $atencion->nombres }} | {{ $atencion->rut }} | {{ $atencion->edad }} años</strong>
        </h5>
    </p>
    {{-- <div class="page-break"></div> --}}
    <table id="orderTable" class="table table-striped" >
        <tr >
            <th valign="top" style="font-size: 15px;">Fecha Atención</th>
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
    @endforeach
  </main>  
</div>
