@extends('layouts.app')

@section('page-title', 'Ajustes')

@section('content')

    <h1 class="page-header">Ajustes</h1>
    @include('partials.messages')    
    
    <form class="form_ajustes" action="{{url('/ajustes')}}" method="post">

      {!! csrf_field() !!}

      <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#planilla">Planilla</a></li>
          
          <li class=""><a data-toggle="tab" href="#oficina">Oficina</a></li>  

          <li class=""><a data-toggle="tab" href="#cargo">Cargo</a></li>

          <li class=""><a data-toggle="tab" href="#profesion">Profesión</a></li>

          <li class=""><a data-toggle="tab" href="#permisos">Permisos y Ausencias</a></li>   
          
          <li class=""><a data-toggle="tab" href="#vacaciones">Vacaciones</a></li>   

          <li class=""><a data-toggle="tab" href="#agregar">Agregar</a></li>

          <li class=""><a data-toggle="tab" href="#correlativo">Correlativo</a></li>

          <li class=""><a data-toggle="tab" href="#bonosalud">Actividad de Salud</a></li>

          <li class=""><a data-toggle="tab" href="#compras">Compras</a></li>


        
      </ul>
      <div class="tab-content">
          <div id="planilla" class="tab-pane fade in active">

              <h4 class="">Visibilidad de Campos</h4>
              <table class="table ajustes_planillas_tabla table-hover table-bordered">
                <thead>
                  <tr>
                    <th>País</th>
                    <th>Fondo Pensión</th>      
                    <th>Impuesto renta</th>
                    <th>Seguridad Social</th>
                    <th>Préstamo</th>                                  
                    <th>Interés</th>                                
                    <th>Otras deducciones</th>        
                  </tr>
                </thead>

                <tbody>

                  @foreach( $paises as $pais)
                  <tr>
                    <td>{{$pais->pais}}</td>
                    <td class="text-center">
                      <div class="">
                        <input type="checkbox" name="pais[{{$pais->id}}][campo_deducciones][]" value="fondo_pension" 
                        @if( in_array('fondo_pension', explode(',', $pais->campo_deducciones)) ) checked @endif >
                        <label class="no-content"></label>
                      </div>
                    </td>
                    <td class="text-center">
                      <div class="">
                        <input type="checkbox" name="pais[{{$pais->id}}][campo_deducciones][]" value="impuesto_renta"
                        @if( in_array('impuesto_renta', explode(',', $pais->campo_deducciones)) ) checked @endif >
                        <label class="no-content"></label>
                      </div>
                    </td>
                    <td class="text-center">
                      <div class="">
                        <input type="checkbox" name="pais[{{$pais->id}}][campo_deducciones][]" value="seguridad_social"
                        @if( in_array('seguridad_social', explode(',', $pais->campo_deducciones)) ) checked @endif >
                        <label class="no-content"></label>
                      </div>
                    </td>
                    <td class="text-center">
                      <div class="">
                        <input type="checkbox" name="pais[{{$pais->id}}][campo_deducciones][]" value="prestamo"
                        @if( in_array('prestamo', explode(',', $pais->campo_deducciones)) ) checked @endif >
                        <label class="no-content"></label>
                      </div>
                    </td>
                    <td class="text-center">
                      <div class="">
                        <input type="checkbox" name="pais[{{$pais->id}}][campo_deducciones][]" value="interes"
                        @if( in_array('interes', explode(',', $pais->campo_deducciones)) ) checked @endif >
                        <label class="no-content"></label>
                      </div>
                    </td>
                    <td class="text-center">
                      <div class="">
                        <input type="checkbox" name="pais[{{$pais->id}}][campo_deducciones][]" value="otras_deducciones"
                        @if( in_array('otras_deducciones', explode(',', $pais->campo_deducciones)) ) checked @endif >
                        <label class="no-content"></label>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>

              <h4 class="">Mes Herencia</h4>
              <table class="table ajustes_planillas_tabla table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Mes</th>
                    <th>Pais</th>  
                  </tr>
                </thead>

                <tbody>
                  @foreach($paises as $pais)
                  <tr>
                    <td>
                      <div>
                        <select name="pais[{{$pais->id}}][meses]" class="form-control">
                          @foreach($months as $month)
                            <option value="{{ $month->id }}" @if($pais->month == $month->id) selected @endif>{{ $month->month }}</option>
                          @endforeach
                        </select>
                        <label class="no-content"></label>
                      </div>
                    </td>
                    <td class="text-center">
                      <div class="">
                        <select name="paises" class="form-control">
                            <option value="">{{ $pais->pais }}</option>
                        </select>
                        <label class="no-content"></label>
                      </div>
                    </td>
                  </tr>
                    @endforeach
                </tbody>
              </table>
              
              <div style="overflow: auto; white-space: nowrap;">
              <h4 class="">Porcentaje para cálculos</h4>
              <table class="table ajustes_planillas_tabla table-hover table-bordered">
                <thead>
                  <tr>
                    <th>País</th>
                             
                    <th>Moneda Símbolo</th>
                    <th>Moneda Nombre</th>
                    <th>Salario Mínimo</th>
                    <th>Bono Mes Catorceavo</th>
                    <th>Porcentaje Pensión</th>      
                    <th>Tipo de Valor Seguridad social</th>                                  
                    <th>Valor Seguridad social</th> 
                    <th>Tipo de Valor Seguridad social Patronal</th>                                  
                    <th>Valor Seguridad social Patronal</th> 

                    @if($pais->id == 7) <!-- Campos nuevos para colombia -->
                      <th>Porcentaje FSP</th>                                  
                      <th>Porcentaje ARL</th>
                      <th>Caja de Compensación</th>
                      <th>Porcentaje EPS</th>
                      <th>Caja Opción</th>
                      <th>Porcentaje ICBF</th>
                      <th>Porcentaje SENA</th>
                      <th>Incapacidad</th> 
                    @endif                                 
                            
                                 
                  </tr>
                </thead>

                <tbody>
                  @foreach($paises as $pais)
                  <tr>
                    <td><b>{{$pais->pais}}</b></td>
                    <td>
                      <input type="text" class="form-control" name="pais[{{$pais->id}}][moneda_simbolo]" 
                      value="{{$pais->moneda_simbolo}}">
                      </td>
                      
                      <td><input type="text" class="form-control" name="pais[{{$pais->id}}][moneda_nombre]" 
                        value="{{$pais->moneda_nombre}}">
                      </td>

                      <td><input type="text" class="form-control" name="pais[{{$pais->id}}][salario_minimo]" 
                        value="{{$pais->salario_minimo}}">
                      </td>

                      <td>
                        <select class="form-control" name="pais[{{$pais->id}}][bono_14]">
                          @if($pais->bono_14 != 'No Disponible')
                            <option value="{{$pais->bono_14}}">{{$pais->bono_14}}</option>
                            <option value="No Disponible">No Disponible</option>
                          @else                          
                            <option value="No Disponible">No Disponible</option>
                          @endif
                          @foreach($months as $month)
                            @if($month->month != $pais->bono_14)
                              <option value="{{$month->month}}">{{$month->month}}</option>
                            @endif
                          @endforeach
                        </select>
                      </td>

                      <td>
                        <input type="number" step="0.01"  class="form-control" name="pais[{{$pais->id}}][porcentaje_pension]" 
                        value="{{$pais->porcentaje_pension}}">
                      </td>
                      <td>
                        <select class="form-control" name="pais[{{$pais->id}}][tipo_seguridad_social]">
                          <option value="valor" {{$pais->tipo_seguridad_social=="valor"? 'selected':''}}>Valor</option>
                          <option value="porcentaje" {{$pais->tipo_seguridad_social=="porcentaje"? 'selected':''}}>Porcentaje</option>
                        </select>
                      </td>
                      <td>
                        <input type="number" step="0.01"  class="form-control" name="pais[{{$pais->id}}][porcentaje_seguridad_social]" 
                        value="{{$pais->porcentaje_seguridad_social}}">
                      </td>
                       <td>
                        <select class="form-control" name="pais[{{$pais->id}}][tipo_seguridad_social_p]">
                          <option value="valor" {{$pais->tipo_seguridad_social_p=="valor"? 'selected':''}}>Valor</option>
                          <option value="porcentaje" {{$pais->tipo_seguridad_social_p=="porcentaje"? 'selected':''}}>Porcentaje</option>
                        </select>
                      </td>
                      <td>
                        <input type="number" step="0.01" class="form-control" name="pais[{{$pais->id}}][seguridad_social_p]" 
                        value="{{$pais->seguridad_social_p}}">
                      </td>

                      @if($pais->id == 7) <!-- Campos nuevos para colombia -->
                        <td>
                          <input type="number" step="0.01" class="form-control" name="pais[{{$pais->id}}][porcentaje_fsp]" 
                          value="{{$pais->porcentaje_fsp}}">
                        </td>
                        <td>
                          <input type="number" step="0.01" class="form-control" name="pais[{{$pais->id}}][porcentaje_arl]" 
                          value="{{$pais->porcentaje_arl}}">
                        </td>
                        <td>
                          <input type="number" step="0.01" class="form-control" name="pais[{{$pais->id}}][porcentaje_parafiscales]" 
                          value="{{$pais->porcentaje_parafiscales}}">
                        </td>
                        <td>
                          <input type="number" step="0.01" class="form-control" name="pais[{{$pais->id}}][porcentaje_eps]" 
                          value="{{$pais->porcentaje_eps}}">
                        </td>
                        <td>
                          <input type="number" step="0.01" class="form-control" name="pais[{{$pais->id}}][porcentaje_caja_opcion]" 
                          value="{{$pais->porcentaje_caja_opcion}}">
                        </td>
                        <td>
                          <input type="number" step="0.01" class="form-control" name="pais[{{$pais->id}}][porcentaje_icbf]" 
                          value="{{$pais->porcentaje_icbf}}">
                        </td>
                        <td>
                          <input type="number" step="0.01" class="form-control" name="pais[{{$pais->id}}][porcentaje_sena]" 
                          value="{{$pais->porcentaje_sena}}">
                        </td>
                        <td>
                          <input type="number" step="0.01" class="form-control" name="pais[{{$pais->id}}][porcentaje_incapacidad]" 
                          value="{{$pais->porcentaje_incapacidad}}">
                        </td>
                      @endif    
                      
                    </tr>


                    @endforeach      
                  </tbody>
              </table></div>

              <h4 class="">Nombre de Campos</h4>
              <table class="table table-hover table-bordered ajustes_planillas_tabla">
                  <thead>
                    <tr>
                      <th>Nombre de campos</th>
                      @foreach($paises as $pais)
                      <th>{{$pais->pais}}</th>
                      @endforeach
                    </tr>
                  </thead>
                  <tbody>            
                    <tr>           
                      <td width="200">Salario base</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][salario_base]" value="{{$pais->campo->salario_base}}" >
                      </td>
                      @endforeach                      
                    </tr>
                    <tr>           
                      <td>Ajustes</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][ajustes]" value="{{$pais->campo->ajustes}}">
                      </td>
                      @endforeach  

                    </tr>
                    <tr>           
                      <td>Salario Total</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][total_salario]" value="{{$pais->campo->total_salario}}">
                      </td>
                      @endforeach                                       
                    </tr>
                    <tr>           
                      <td>Catorceavo mes</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][catorceavo]" value="{{$pais->campo->catorceavo}}">
                      </td>
                      @endforeach                                  
                    </tr>

                    <tr>           
                      <td>Préstamo</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][prestamo]" value="{{$pais->campo->prestamo}}">
                      </td>
                      @endforeach                      
                    </tr>
                    <tr>           
                      <td>Interes</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][interes]" value="{{$pais->campo->interes}}">
                      </td>
                      @endforeach

                    </tr>
                    <tr>           
                      <td>Otras deducciones</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][otras_deducciones]" value="{{$pais->campo->otras_deducciones}}">
                      </td>
                      @endforeach                      
                    </tr>
                    <tr>           
                      <td>Impuestos</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][impuestos]" value="{{$pais->campo->impuestos}}">
                      </td>
                      @endforeach                      
                    </tr>
                    <tr>           
                      <td>Total deducciones</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][total_deducciones]" value="{{$pais->campo->total_deducciones}}">
                      </td>
                      @endforeach                       
                    </tr>
                    <tr>           
                      <td>Seguridad Social</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][seguridad_social]" value="{{$pais->campo->seguridad_social}}">
                      </td>
                      @endforeach                      
                    </tr>
                    <tr>           
                      <td>Seguridad Social Patronal</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][seguridad_social_p]" value="{{$pais->campo->seguridad_social_patronal}}">
                      </td>
                      @endforeach                    
                    </tr>
                    <tr>
                      <td>Líquido a Recibir</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][liquido]" value="{{$pais->campo->liquido}}">
                      </td>
                      @endforeach                       
                    </tr>
                    <tr>
                      <td>Acumulado Aguinaldo</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][acumulado_aguinaldo]" value="{{$pais->campo->acumulado_aguinaldo}}">
                      </td>
                      @endforeach                       
                    </tr>
                    <tr>
                      <td>Acumulado indemnización</td>
                      @foreach($paises as $pais)
                      <td>
                        <input type="text" class="form-control input_inline" 
                        name="campo[{{$pais->id}}][acumulado_indemnizacion]" value="{{$pais->campo->acumulado_indemnizacion}}">
                      </td>
                      @endforeach                       
                    </tr> 

                  </tbody>
              </table>

              <div class=" row text-right">
                <div class="col-xs-12">
                  <button type="submit" class="btn btn_color"> Guardar Ajustes </button>
                </div>
              </div>

          </div>
          
          <div id="oficina" class="tab-pane fade">              
             
              <table class="table ajustes_planillas_tabla table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Oficina</th>
                    <th>Central</th>                              
                    <th>Teléfono</th>
                    <th>NIT</th>
                    <th>Num. Patronal</th> 
                    <th>Dirección</th>                      
                  </tr>
                </thead>

                <tbody>
                  @foreach( $oficinas as $oficina)
                  <tr>
                    <td>{{$oficina->oficina}}</td>
                    <td align="center" valign="middle" >{{$oficina->central?'SI':'NO'}}</td>
                    <td>
                      <input type="text" class="form-control" name="oficina[{{$oficina->id}}][telf]" 
                      value="{{$oficina->telf}}">
                    </td>

                    <td>
                      <input type="text" class="form-control" name="oficina[{{$oficina->id}}][nit]" 
                      value="{{$oficina->nit}}">
                    </td>
                    <td>
                     <input type="text" class="form-control" name="oficina[{{$oficina->id}}][num_patronal]" 
                      value="{{$oficina->num_patronal}}">
                    </td>
                    <td>
                      <textarea type="text" class="form-control" name="oficina[{{$oficina->id}}][direccion]" 
                      >{{$oficina->direccion}} </textarea>
                    </td>                   
                  </tr>
                  @endforeach
                </tbody>
              </table>

              <div class=" row text-right">
                <div class="col-xs-12">
                  <button type="submit" class="btn btn_color"> Guardar Ajustes </button>
                </div>
              </div>
          </div>
          
          <div id="cargo" class="tab-pane fade">
             
            <div class="col-md-12">
            <table class="table ajustes_planillas_tabla dataTables-cargo-profesiones table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Nombre del Cargo</th>
                    <th>Superior</th>                          
                    <th>Opciones</th>                                       
                  </tr>
                </thead>
                <tbody>
                  @foreach( $cargos as $cargo)
                  <tr>                  
                    <td>                      
                      <input type="text" class="form-control" name="cargo[{{$cargo->id}}][cargo]" 
                      value="{{$cargo->cargo}}" {{$cargo->id==21 ? 'readonly':'' }}>
                    </td>

                    <td>
                      <select class="form-control" name="cargo[{{$cargo->id}}][superior_id]">
                        <option value="{{$cargo->superior_id}}">{{$cargo->superior->cargo}}</option>
                        @foreach($cargos as $car)
                          @if($car->id != $cargo->superior_id)
                            <option value="{{$car->id}}">{{$car->cargo}}</option>
                          @endif
                        @endforeach 
                      </select>
                    </td>

                    
                    <td>                     
                    
                      <a href='{{ url("/delete/cargo/$cargo->id") }}' class="btn btn_color" 
                        title="Eliminar Cargo {{$cargo->cargo}}"
                            data-toggle="tooltip"
                            data-placement="top"
                            data-method="DELETE"
                            data-confirm-title="Eliminar Cargo {{$cargo->cargo}}"
                            data-confirm-text="Esta seguro que quiere eliminar este cargo"
                            data-confirm-delete="Borrar"
                            style="background: #F44336;" >
                            <i class="glyphicon glyphicon-trash"></i>
                        </a>    
                    </td>                   
                  </tr>
                  @endforeach
                </tbody>
            </table>
            </div>

            <div class=" row text-right">
                <div class="col-xs-12">
                  <button type="submit" class="btn btn_color"> Guardar Ajustes </button>
                </div>
            </div>
          </div>

          <div id="profesion" class="tab-pane fade">
            
            <div class="col-sm-6">
             <table class="table ajustes_planillas_tabla dataTables-cargo-profesiones table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Profesión</th>                          
                    <th>Opciones</th>                                       
                  </tr>
                </thead>
                <tbody>
                  @foreach( $profesiones as $profesion)
                  <tr>                  
                    <td>
                      <input type="text" class="form-control" name="profesion[{{$profesion->id}}][profesion]" 
                      value="{{$profesion->profesion}}" {{$profesion->id==21 ? 'readonly':'' }} >
                    </td>
                    <td>    
                      <a href='{{ url("/delete/profesion/$profesion->id") }}' class="btn btn_color" 
                        title="Eliminar Cargo {{$profesion->profesion}}"
                            data-toggle="tooltip"
                            data-placement="top"
                            data-method="DELETE"
                            data-confirm-title="Eliminar Cargo {{$profesion->profesion}}"
                            data-confirm-text="Esta seguro que quiere eliminar este profesion"
                            data-confirm-delete="Borrar"
                            style="background: #F44336;" >
                            <i class="glyphicon glyphicon-trash"></i>
                        </a>    
                    </td>                   
                  </tr>
                  @endforeach
                </tbody>
              </table>
            
            </div>
            <div class=" row text-right">
                <div class="col-xs-12">
                  <button type="submit" class="btn btn_color"> Guardar Ajustes </button>
                </div>
            </div>
          </div>

          <div id="permisos" class="tab-pane fade">
            
            <div class="col-sm-6">
               <h4 class="">Parámetros</h4>
              <table class="table ajustes_planillas_tabla table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Pais</th>
                             
                    <th>Nº de Horas máximas por permiso</th>
                    <th>Nº de Días máximos por permiso</th>                    
                                 
                  </tr>
                </thead>

                <tbody>
                  @foreach($paises as $pais)
                  <tr>
                    <td><b>{{$pais->pais}}</b></td>
                    <td>
                      <input type="text" class="form-control" name="pais[{{$pais->id}}][n_horas]" 
                      value="{{$pais->n_horas}}">
                    </td>

                    <td>
                      <input type="text" class="form-control" name="pais[{{$pais->id}}][n_dias]" 
                      value="{{$pais->n_dias}}">
                    </td>                
                      
                  </tr>


                    @endforeach      
                  </tbody>
              </table>
            </div>
            
            <div class="col-sm-6">
              <h4 class="">Motivos de Permiso</h4>
             <table class="table ajustes_planillas_tabla dataTables-cargo-profesiones table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Motivo del permiso</th>                          
                    <th>Opciones</th>                                       
                  </tr>
                </thead>
                <tbody>
                  @foreach( $motivos as $motivo)
                  <tr>                  
                    <td>
                      <input type="text" class="form-control" name="motivo_permiso[{{$motivo->id}}][motivo]" 
                      value="{{$motivo->motivo}}" >
                    </td>
                    <td>    
                      <a href='{{ url("/delete/motivo/$motivo->id") }}' class="btn btn_color" 
                        title="Eliminar Cargo {{$motivo->motivo}}"
                            data-toggle="tooltip"
                            data-placement="top"
                            data-method="DELETE"
                            data-confirm-title="Eliminar Cargo {{$motivo->motivo}}"
                            data-confirm-text="Esta seguro que quiere eliminar este motivo"
                            data-confirm-delete="Borrar"
                            style="background: #F44336;" >
                            <i class="glyphicon glyphicon-trash"></i>
                        </a>    
                    </td>                   
                  </tr>
                  @endforeach
                </tbody>
              </table>
            
            </div>
            <div class=" row text-right">
                <div class="col-xs-12">
                  <button type="submit" class="btn btn_color"> Guardar Ajustes </button>
                </div>
            </div>
          </div>

          <div id="vacaciones" class="tab-pane fade">
            
            <div class="col-sm-8">
               <h4 class="">Parámetros</h4>
              <table class="table ajustes_planillas_tabla table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Pais</th>                             
                    
                    <th>Nº de Días por Mes</th>
                    <th>Nº de Días por Mes Dic</th>
                    <th>Mes de Inicio</th>
                                 
                  </tr>
                </thead>

                <tbody>
                  @foreach($paises as $pais)
                  <tr>
                    <td><b>{{$pais->pais}}</b></td>
                    <td>
                      <input type="text" class="form-control" name="pais[{{$pais->id}}][vacaciones]" 
                      value="{{$pais->vacaciones}}">
                    </td>
                    <td>
                      <input type="text" class="form-control" name="pais[{{$pais->id}}][vac_dic]"
                      value="{{$pais->vac_dic}}">
                    </td>
                    <td>
                      <select class="form-control" name="pais[{{$pais->id}}][mes_vac]">
                        <option value="{{$pais->mes_vac}}" selected>{{$pais->mes_vacaciones->month}}-2019</option>
                        @foreach($months as $month)
                          @if($month->id != $pais->mes_vac)
                            <option value="{{$month->id}}">{{$month->month}}-2019</option>
                          @endif
                        @endforeach
                      </select>
                    </td>                                 
                      
                  </tr>
                  @endforeach      
                  </tbody>
              </table>
            </div>           
            
            <div class=" row text-right">
                <div class="col-xs-12">
                  <button type="submit" class="btn btn_color"> Guardar Ajustes </button>
                </div>
            </div>
          </div>
          </form>

          <div id="agregar" class="tab-pane fade">

             <div class="row">     
              
                  <form class="form_ajustes"  action="{{url('/create/cargo_profesion')}}" method="get">
                    <div class="form-group col-xs-12" style="  margin-bottom: 30px;" >
                          <input  type="text" required class="form-control input_inline" name="cargo">
                          <select class="form-control input_inline" name="superior_cargo">
                            <option value="{{$cargo->superior_id}}">{{$cargo->superior->cargo}}</option>
                            @foreach($cargos as $car)
                              @if($car->id != $cargo->superior_id)
                                <option value="{{$car->id}}">{{$car->cargo}}</option>
                              @endif
                            @endforeach 
                          </select>
                          <input  type="hidden"  name="tipo" value="cargo">
                          <button type="submit" class="btn btn_color">Agregar Cargo</button>
                    </div>
                  </form>
                

              
                  <form class="form_ajustes" id="form_profesion" action="{{url('/create/cargo_profesion')}}" method="get">
                    <div class="form-group col-xs-12" style="  margin-bottom: 30px;" >
                          <input type="text" required class="form-control input_inline" name="profesion">
                          <input  type="hidden"  name="tipo" value="profesion">
                          <button type="submit" class="btn btn_color">Agregar Profesión</button>
                    </div>
                  </form>            

              
                  <form class="form_ajustes"  action="{{url('/create/motivo_permiso')}}" method="get">
                    <div class="form-group col-xs-12" style="  margin-bottom: 30px;" >
                          <input type="text" required class="form-control input_inline" name="motivo_permiso">
                          <input  type="hidden"  name="tipo" value="motivo_permiso">
                          <button type="submit" class="btn btn_color">Agregar Motivo de Permiso</button>
                    </div>
                  </form>
            </div>
           
          </div>

          <div id="correlativo" class="tab-pane fade">

            <div class="row">

                  <form class="form_ajustes"  action="{{url('/create/correlativo')}}" method="get">
                    <div class="form-group col-xs-12" style="  margin-bottom: 30px;" >
                          <input  type="text" required class="form-control input_inline" name="correlativo" value='{{$correlativo->value}}'>
                          <button type="submit" class="btn btn_color">Guardar Correlativo</button>
                    </div>
                  </form>
            </div>

            <div class="row">

                <div class="col-sm-8">
               <h4 class="">Correlativos de Compras</h4>
              <table class="table table-hover table-bordered ajustes_planillas_tabla">
                <thead>
                  <tr>
                    <th>Oficina</th>
                    <th>Correlativo Solicitud</th>                             
                    <th>Correlativo Decisión</th>
                    <th>Correlativo Compra</th>
                                 
                  </tr>
                </thead>

                <tbody>
                  @foreach($oficinas as $oficina)
                  <tr>
                    <td><b>{{$oficina->oficina}}</b></td>
                    <td>
                      <input type="number" class="form-control" name="oficina[{{$oficina->id}}][correlativo_solicitud]" 
                      value="{{$oficina->correlativo_solicitud}}">
                    </td>
                    <td>
                      <input type="number" class="form-control" name="oficina[{{$oficina->id}}][correlativo_decision]"
                      value="{{$oficina->correlativo_decision}}">
                    </td>
                    <td>
                      <input type="number" class="form-control" name="oficina[{{$oficina->id}}][correlativo_compra]"
                      value="{{$oficina->correlativo_compra}}">
                    </td>                             
                      
                  </tr>
                  @endforeach      
                  </tbody>
              </table>
            </div>

            <div class=" row text-right">
                <div class="col-xs-12">
                  <button type="submit" class="btn btn_color"> Guardar Correlativos Compras </button>
                </div>
            </div>  
            </div>
          </div>

            <div id="bonosalud" class="tab-pane fade">
              
              <div class="col-sm-8">
               <h4 class="">Parámetros</h4>
              <table class="table table-hover table-bordered ajustes_planillas_tabla">
                <thead>
                  <tr>
                    <th>País</th>                             
                    <th>SK Al Año</th>
                                 
                  </tr>
                </thead>

                <tbody>
                  @foreach($paises as $pais)
                  <tr>
                    <td><b>{{$pais->pais}}</b></td>
                    <td>
                      <input type="number" step="0.01" class="form-control" name="pais[{{$pais->id}}][corona_sueca]" 
                      value="{{$pais->corona_sueca}}">
                    </td>                            
                      
                  </tr>
                  @endforeach      
                  </tbody>
              </table>
            </div>

            <div class=" row text-right">
                <div class="col-xs-12">
                  <button type="submit" class="btn btn_color"> Guardar Ajustes </button>
                </div>
            </div>

          </div>

          <div id="compras" class="tab-pane fade">
              <div class="col-sm-6">
               <h4 class="">Parámetros</h4>
              <table class="table table-hover table-bordered ajustes_planillas_tabla">
                <thead>
                  <tr>
                    <th>País</th>                             
                    <th>Tasa Conversión $</th>
                    <th>Tasa Conversión Corona</th>
                                 
                  </tr>
                </thead>

                <tbody>
                  @foreach($paises as $pais)
                  <tr>
                    <td><b>{{$pais->pais}}</b></td>
                    <td>
                      <input type="number" step="0.0000001" class="form-control" name="pais[{{$pais->id}}][tasa_conv_usd]" 
                      value="{{$pais->tasa_conv_usd}}">
                    </td>
                    <td>
                      <input type="number" step="0.0000001" class="form-control" name="pais[{{$pais->id}}][tasa_conv_corona]"
                      value="{{$pais->tasa_conv_corona}}">
                    </td>                             
                      
                  </tr>
                  @endforeach      
                  </tbody>
              </table>
            </div>

            <div class="col-sm-6">
               <h4 class="">Tipos Compra Parámetros</h4>
              <table class="table table-hover table-bordered ajustes_planillas_tabla">
                <thead>
                  <tr>
                    <th>Valor Genérico</th>                             
                    <th>Valor Inicial</th>
                    <th>Valor Final</th>
                                 
                  </tr>
                </thead>

                <tbody>
                  @foreach($tiposCompra as $tipo)
                  <tr>
                    <td><b>{{$tipo->valor_generico}}</b></td>
                    <td>
                      <input type="number" step="0.01" class="form-control" name="tiposCompra[{{$tipo->id}}][valor_inicial]" 
                      value="{{$tipo->valor_inicial}}">
                    </td>
                    <td>
                      <input type="number" step="0.01" class="form-control" name="tiposCompra[{{$tipo->id}}][valor_final]"
                      value="{{$tipo->valor_final}}">
                    </td>                             
                      
                  </tr>
                  @endforeach      
                  </tbody>
              </table>
            </div>

            <div class="col-sm-6">
              <h4>Tipos de Documentos de Compra</h4>
              <table class="table ajustes_planillas_tabla dataTables-cargo-profesiones table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Nombre</th>                          
                    <th>Opciones</th>                                       
                  </tr>
                </thead>
                <tbody>
                  @foreach( $tiposDoc as $doc)
                  <tr>                  
                    <td>
                      <input type="text" class="form-control" name="profesion[{{$doc->id}}][tipodoc]" 
                      value="{{$doc->nombre}}">
                    </td>
                    <td>    
                      <a href='{{ url("/delete/tipodoc/$doc->id") }}' class="btn btn_color" 
                        title="Eliminar Tipo de Documento de Compras {{$doc->nombre}}"
                            data-toggle="tooltip"
                            data-placement="top"
                            data-method="DELETE"
                            data-confirm-title="Eliminar Tipo de Documento de Compras {{$doc->nombre}}"
                            data-confirm-text="Esta seguro que quiere eliminar este tipo de documento"
                            data-confirm-delete="Borrar"
                            style="background: #F44336;" >
                            <i class="glyphicon glyphicon-trash"></i>
                        </a>    
                    </td>                   
                  </tr>
                  @endforeach
                </tbody>
              </table>
            
            </div>

            <div class="col-sm-6">
               <h4 class="">Impuestos y Deducciones en Pagos</h4>
              <table class="table table-hover table-bordered ajustes_planillas_tabla">
                <thead>
                  <tr>
                    <th>Oficina</th>                             
                    <th>Impuesto</th>
                    <th>Descuento ISR</th>       
                  </tr>
                </thead>

                <tbody>
                  @foreach($oficinas as $oficina)
                  <tr>
                    <td><b>{{$oficina->oficina}}</b></td>
                    <td>
                      <input type="number" step="0.0000001" class="form-control" name="oficina[{{$oficina->id}}][imp_pago_compra]" 
                      value="{{$oficina->imp_pago_compra}}">
                    </td>
                    <td>
                      <input type="number" step="0.0000001" class="form-control" name="oficina[{{$oficina->id}}][isr_pago_compra]" value="{{$oficina->isr_pago_compra}}">
                    </td>                           
                  </tr>
                  @endforeach      
                  </tbody>
              </table>
            </div>
              <div class="row">

                <form class="form_ajustes"  action="{{url('create/tipodoc_compra')}}" method="get">
                    <div class="form-group col-xs-12" style="  margin-bottom: 30px;" >
                          <input  type="text" required class="form-control input_inline" name="tipo_doc_compra">
                          <button type="submit" class="btn btn_color">Guardar Tipo de Documento de Compra</button>
                    </div>
                  </form>
            </div>

            <div class=" row text-right">
                <div class="col-xs-12">
                  <button type="submit" class="btn btn_color"> Guardar Ajustes </button>
                </div>
            </div>

          </div>



      </div>
      @role(['Admin','WebMaster'])
      <div class="row">
        <div class="col-xs-12">
          <form class="form_email" class="ajax_email_prueba">
          <input type="email" required class="form-control email_prueba" style="display: inline-block;width: auto;">
          
          <button type="submit" class=" email_btn btn btn_color"> Enviar correo </button>
          
          <label class="load_email_prueba"></label>
          </form>
        </div>
      </div>
      @endrole
            
        
      


@stop

@section('scripts')

<script> 

$(document).on('submit', '.form_email', function(event) {
    event.preventDefault();
    email=$('input.email_prueba').val();
    $('.load_email_prueba').html('<i class="glyphicon glyphicon-refresh"></i> Enviando');
    
    $('.form_email input,.email_btn').attr('disabled', true);
    
    $.ajax({
      url: '{{url("/email_prueba")}}',
      type:'post',
      dataType: 'json',
      data: {email: email},
    })
    .done(function(data) {
      $('.load_email_prueba').html('correoo enviado');
    })
    .fail(function() {
      $('.load_email_prueba').html('correoo fallido');
    })
    .always(function(data) {
      console.log(data)
      $('.form_email input,.email_btn').attr('disabled', false);
    });
    
}); 
$(document).on('submit', '.form_ajustes', function(event) {        
    $(this)
    .find(':input[type=submit]')
    .attr('disabled', true)
    .html('<i class="glyphicon glyphicon-refresh"></i> Guardando Informacion')
    .css('color', '#000');;
  }); 
//////////////////////////////////////////////////////////////////////////////////
  //salvador calculo acumulado aguinaldo
  
</script>
     
@stop
