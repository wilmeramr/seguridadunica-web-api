<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Reporte de Ventas</title>

	<!-- cargar a través de la url del sistema -->

		<link rel="stylesheet" href="{{ asset('css/custom_pdf.css') }}">
		<link rel="stylesheet" href="{{ asset('css/custom_page.css') }}">

	<!-- ruta física relativa OS -->
    <!--
	<link rel="stylesheet" href="{{ public_path('css/custom_pdf.css') }}">
	<link rel="stylesheet" href="{{ public_path('css/custom_page.css') }}">
-->
</head>
<body>

	<header class="header" style="top: 1px;">
		<table cellpadding="0" cellspacing="0" width="100%">

			<tr>
				<td width="30%" style="vertical-align: top; padding-top: 20px;padding-left: 50px; position: relative">
					<img src={{$logo }} alt="" class="invoice-logo">
				</td>

				<td width="70%" class="text-left text-company" style="vertical-align: top; padding-top: 10px">
                    <span align="center" style="font-size: 16px; font-weight: bold;">CONTROL DE ACCESO </span>
                    <br>
					@if($reportType == 0)
					<span style="font-size: 16px"><strong>Reporte de Entradas/Salidas del Día</strong></span>
					@else
					<span style="font-size: 16px"><strong>Reporte de Entradas/Salidas por Fechas</strong></span>
					@endif

					@if($reportType !=0)
					<span style="font-size: 16px"><strong>( {{$dateFrom}} al {{$dateTo}})</strong></span>
					@else
					<span style="font-size: 16px"><strong>( {{ \Carbon\Carbon::now()->format('d-M-Y')}})</strong></span>
					@endif
					<br>
					<span style="font-size: 14px">{{$user}}</span>
				</td>
			</tr>
		</table>
	</header>

<main>

		<table cellpadding="0" cellspacing="0" class="table-items" width="100%">
			<thead>
				<tr>
					<th width="10%">ESTADO</th>
					<th width="12%">DOCUMENTO</th>
					<th width="10%">NOMBRE</th>
					<th width="12%">VISITA A</th>
					<th width="10%">LOTE</th>
					<th width="18%"> FECHA ENTRADA</th>
					<th width="18%"> FECHA SALIDA</th>


				</tr>
			</thead>
			<tbody>
				@foreach($data as $ingreso)
                <tr>
					<td align="center">  <span class="badge {{$ingreso->ingr_salida == null ? 'badge-success':'badge-danger'}} text-uppercase">{{ $ingreso->ingr_salida == null ? 'SIN SALIDA':'COMPLETADO'}}</span></td>
					<td align="center">{{$ingreso->ingr_documento}}</td>
					<td align="center">{{$ingreso->ingr_nombre}}</td>
					<td align="center">{{$ingreso->us_name}}</td>
					<td align="center">{{$ingreso->lot_name}}</td>
					<td align="center">{{$ingreso->ingr_entrada}}</td>
					<td align="center">{{$ingreso->ingr_salida}}</td>
                </tr>


        <tr>
            <td colspan="7">
                <table  cellpadding="0" cellspacing="0" class="table-items" width="100%">


                     <tr  >
                                    <th  width="10%">  Art Vto</th>
                                    <th width="10%">  N° Licencia</th>
                                    <th width="10%">  Lic. Vto</th>
                                <th width="10%">  Auto Marca</th>
                                <th width="10%">  Auto Modelo</th>
                                <th width="10%">  Auto Color</th>
                                <th width="10%">  Seguro Nombre</th>
                                <th width="10%">  N° Póliza</th>
                                <th  width="10%">  Seguro Vto</th>

                                </tr>



                                <tr>
                                    <td align="center">{{$ingreso->ingr_art_vto}}</td>
                                    <td align="center">{{$ingreso->ingr_licencia_numero}}</td>
                                    <td align="center">{{$ingreso->ingr_licencia_vto}}</td>
                                    <td align="center">{{$ingreso->ingr_auto_marca}}</td>
                                    <td align="center">{{$ingreso->ingr_auto_modelo}}</td>
                                    <td align="center">{{$ingreso->ingr_auto_color}}</td>
                                <td align="center">{{$ingreso->ingr_seguro_nombre}}</td>
                                <td align="center">{{$ingreso->ingr_seguro_numero}}</td>
                                <td align="center">{{$ingreso->ingr_seguro_vto}}</td>

                                </tr>



                                </table>
            </td>

</tr>






                <div class="page-break"></div>
				@endforeach
			</tbody>
			<tfoot>
				<tr>

					<td class="text-center">
						{{count($data)}}
					</td>
					<td colspan="3"></td>
				</tr>
			</tfoot>
		</table>

</main>


    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(20, 550, "Pagina $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ');
        }
    </script>
</body>
</html>

