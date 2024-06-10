
<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget">
            <div class="widget-heading">
                <h4 class="card-title text-center">
                    <b>{{$componentName}}</b>
                </h4>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>Elige el Lote</h6>
                                <div class="form-group">
                                    <select wire:model="userId"   class="form-control">
                                         <option value="0">Seleccione</option>
                                        @foreach ($usersQuery as $user )
                                        <option value="{{$user->lot_id}}">{{$user->lot_name}}
                                        </option>

                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h6>Elige el tipo de busqueda</h6>
                                <div class="form-group">
                                    <select wire:model="reportType"class="form-control">
                                        <option value="0">Entradas del dia</option>
                                        <option value="1">Entradas por fecha</option>


                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h6>Fecha desde</h6>
                                <div class="form-group">
                                    <input type="text" wire:model="dateFrom" class="form-control basicFlatpickr" placeholder="Click para elegir">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h6>Fecha hasta</h6>
                                <div class="form-group">
                                    <input type="text" wire:model="dateTo" class="form-control basicFlatpickr" placeholder="Click para elegir">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <button wire:click="$refresh" class="btn btn-dark btn-block">
                                    Consultar
                                </button>
                                <a class="btn btn-dark btn-block {{ count($this->ingresos) < 1 || $this->userId == 0 ? 'disabled':'' }}"
                                 href="{{ url('report/pdf' . '/'. $userId. '/'. $reportType. '/'. $dateFrom . '/'. $dateTo )}}" target="_blank">Generar PDF</a>

                                 {{--  <a class="btn btn-dark btn-block {{ count($this->ingresos) < 1 ? 'disabled':'' }}"
                                 href="{{ url('report/excel' . '/'. $userId. '/'. $reportType. '/'. $dateFrom . '/'. $dateTo )}}" target="_blank">Exportar a Excel</a>  --}}
                            </div>
                        </div>
                    </div>

                    <div class="col-ms-12 col-md-9">
                        <div class="table-responsive">
                            <table class="table table-bordered table striped mt-1">
                                <thead class="text-white" style="background: #3B3F5C;">
                                    <th class="table-th text-white">
                                        ESTADO
                                    </th>
                                    <th class="table-th text-white">
                                        DOCUMENTO
                                    </th>
                                    <th class="table-th text-white">
                                        NOMBRE
                                    </th>
                                    <th class="table-th text-white">
                                        VISITA A
                                    </th>
                            <th class="table-th text-white">
                                LOTE
                            </th>
                            <th class="table-th text-white text-center">
                                FECHA ENTRADA
                            </th>
                            <th class="table-th text-white">
                                FECHA SALIDA
                            </th>

                            </thead>

                            <tbody>

                                @if (count($this->ingresos) < 1)

                                <tr>
                                    <td colspan="7">
                                        <h5> Sin resultados</h5>
                                    </td>
                                </tr>

                                @endif
                                @foreach ($this->ingresos as $ingreso )

                                <tr >
                                    <td class="text-center">
                                        <span class="badge {{$ingreso->ingr_salida == null ? 'badge-success':'badge-danger'}} text-uppercase">{{ $ingreso->ingr_salida == null ? 'SIN SALIDA':'COMPLETADO'}}</span>
                                     </td>

                                     <td class="text-center">
                                        <h6>{{$ingreso->ingr_documento}}</h6>
                                    </td>

                                    <td class="text-center">
                                        <h6>{{$ingreso->ingr_nombre}}</h6>
                                    </td>

                                    <td>
                                        <h6>{{$ingreso->us_name}}</h6>
                                    </td>

                                    <td>
                                        <h6>{{$ingreso->lot_name}}</h6>
                                    </td>
                                    <td>
                                        <h6>{{\Carbon\Carbon::parse($ingreso->ingr_entrada)->format('d-m-Y H:i')}}</h6>
                                    </td>
                                    <td>
                                        <h6>{{\Carbon\Carbon::parse($ingreso->ingr_salida)->format('d-m-Y H:i')}}</h6>
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
    </div>

</div>
