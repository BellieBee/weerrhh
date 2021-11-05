
    <table border="1">
      <thead>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr>
          <td colspan="5">NIT: {{$oficina->nit}}</td>
        </tr>

        <tr>
          <td colspan="5">Nº PATRONAL: {{$oficina->num_patronal}}</td>
        </tr>
        <tr>
          <td colspan="6">{{$oficina->direccion}}</td>
        </tr>
        <tr>
          <td colspan="6">Telf:{{$oficina->telefono}}</td>
        </tr>
        <tr>
          <td colspan="3">{{$pais->pais}}</td>
        </tr>
        <tr>
          <td colspan="8" height="15" align="center" style="text-transform: uppercase;">
            <b>FERIADOS DEL MES {{ $mes->month }}</b>
          </td>
        </tr> 
        <tr> 
          <td width="25" align="center"><b>Lunes</b></td> 
          <td width="25" align="center"><b>Martes</b></td> 
          <td width="25" align="center"><b>Miércoles</b></td> 
          <td width="25" align="center"><b>Jueves</b></td> 
          <td width="25" align="center"><b>Viernes</b></td> 
          <td width="25" align="center"><b>Sábado</b></td> 
          <td width="25" align="center"><b>Domingo</b></td> 
        </tr> 
      </thead> 
      <tbody> 
        @foreach ($calendario as $dias)
          <tr>
            @for($i = 1; $i <= 7; $i++)
              @if(isset($dias[$i]))
                  @if(in_array($dias[$i], $dia))
                    <td height="40" align="left">
                      <b>{{ $dias[$i] }}</b>
                      @foreach($dia as $key => $value)
                      @if($value == $dias[$i])
                      <br>
                      <b>{{ $desc[$key] }}</b>
                        @if($leyend[$key] == 1) <!-- Guatemala -->
                          <b>(G)</b>
                        @elseif($leyend[$key] == 2) <!-- Bolivia -->
                          <b>(B)</b>
                        @elseif($leyend[$key] == 3) <!-- Nicaragua -->
                          <b>(N)</b>
                        @elseif($leyend[$key] == 4) <!-- Honduras -->
                          <b>(H)</b>
                        @elseif($leyend[$key] == 5) <!-- Paraguay -->
                          <b>(P)</b>
                        @elseif($leyend[$key] == 6) <!-- Salvador -->
                          <b>(S)</b>
                        @endif
                      @endif
                      @endforeach
                    </td>
                  @else  
                  <td height="40" align="left">{{ $dias[$i] }}</td>
                @endif
              @else
                <td></td>
              @endif
            @endfor
          </tr>
        @endforeach
        <tr></tr>
        <tr></tr>
        <tr>
          <td td colspan="2" height="15" align="center" style="text-transform: uppercase;">
            <b>LEYENDA DE FERIADOS POR PAISES</b>
          </td>
        </tr>
        <tr>
          <td height="15" align="center"><b>Paises</b></td>
          <td height="15" align="center"><b>Sigla</b></td>
        </tr>
        @foreach($paises as $p)
          @if($p == 'Guatemala')
            <tr>
              <td height="15" align="center">{{ $p }}</td>
              <td height="15" align="center">G</td>
            </tr>
          @elseif($p == 'Bolivia')
            <tr>
              <td height="15" align="center">{{ $p }}</td>
              <td height="15" align="center">B</td>
            </tr>
          @elseif($p == 'Nicaragua')
            <tr>
              <td height="15" align="center">{{ $p }}</td>
              <td height="15" align="center">N</td>
            </tr>
          @elseif($p == 'Honduras')
            <tr>
              <td height="15" align="center">{{ $p }}</td>
              <td height="15" align="center">H</td>
            </tr>
          @elseif($p == 'Paraguay')
            <tr>
              <td height="15" align="center">{{ $p }}</td>
              <td height="15" align="center">P</td>
            </tr>
          @elseif($p == 'Salvador')
            <tr>
              <td height="15" align="center">{{ $p }}</td>
              <td height="15" align="center">S</td>
            </tr>
          @elseif($p == 'Colombia')
          <tr>
            <td height="15" align="center">{{ $p }}</td>
            <td height="15" align="center">C</td>
          </tr>
          @endif
        @endforeach
      </tbody> 
    </table>
