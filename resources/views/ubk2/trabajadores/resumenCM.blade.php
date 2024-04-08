@extends('layouts.app')
@section('content')
{!! MigasFacade::render('ubk2/trab/read') !!}

<style>
     .container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        /*border: 1px solid #ccc;*/
        margin: 0px;
        padding: 0px;

        gap: 1px;
        /* Espacio entre las columnas (ajustar según sea necesario) */
    }

    /* Estilos adicionales para los elementos dentro de la cuadrícula (opcional) */
    .item {
     
        padding: 1px;
        /*border: 1px solid #ccc;*/
        text-align: center;
        box-sizing: border-box;
      
        
    }


    .grid_header {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        /* Espacio entre las columnas (ajustar según sea necesario) */
    }

    /* Estilos adicionales para los elementos dentro de la cuadrícula (opcional) */


    .left-column {
        /* Centra el texto a la derecha en la columna izquierda */
        text-align: left;
      
    }

    .right-column {
        /* Centra el texto a la izquierda en la columna derecha */
        text-align: right;
        
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 4px;
        text-align: center;
        /* Centrado horizontal */

    }

    td {
        vertical-align: middle;
        /* Centrado vertical */
    }

    td[colspan] {
        text-align: center;
    }

    td[rowspan] {
        text-align: center;
    }

    .centrado {
        display: flex;
        justify-content: center;
        /* Centrado horizontal */
        align-items: center;
        /* Centrado vertical */
        /*height: 300px; /* Altura del contenedor */
        /*border: 1px solid #ccc; /* Borde para visualización */
        text-align: center;
        /* Centrado horizontal */
    }
</style>

<div style="margin:1rem;">


<div class="card">
    <div class="card-header">
        <div class="grid_header">
            <div class="item left-column">
            <h5>Resumen de la carga masiva de trabajadores</h5>
            </div>
            <div class="item right-column">
             <a href="#">OK</a> - <a href="#">Descargar Fallidos</a>
            </div>
        </div>
    </div>
    <div class="card-body">
    <table>
    <thead>
        <tr>
            <th rowspan="2">Estado</th>
            <th rowspan="2"> <div class="centrado">#</div></th>
            <th rowspan="2">
                <div class="centrado">Trabajador</div>
            </th>
            <th colspan="3">
                <div class="centrado">R.U.N.</div>
            </th>
            <th colspan="2">
                <div class="centrado">Móvil</div>
            </th>

            <th rowspan="2">
                <div class="centrado">Correo</div>
                <div class="centrado"><small>El Correo está en uso</small></div>
            </th>
            <th rowspan="2">
                <div class="centrado">Área</div>
                <div class="centrado"><small>No se ingreso</small></div>
            </th>


        </tr>
        <tr>

            <th>
                <div class="centrado">Formato</div>
                <div class="centrado"><small>Revise guión y DV</small></div>
            </th>
            <th>
                <div class="centrado">Inválido</div>
                <div class="centrado"><small>Revise nro de RUN y DV</small></div>
            </th>
            <th>
                <div class="centrado">Utilizado</div>
                <div class="centrado"><small>El R.U.N. ya está en uso</small></div>
            </th>
            <th>
                <div class="centrado">Formato</div>
                <div class="centrado"><small>Revise cant. de digitos (11)</small></div>
            </th>
            <th>
                <div class="centrado">Utilizado</div>
                <div class="centrado"><small>El Móvil está en uso</small></div>
            </th>

        </tr>
    </thead>
    <tbody>
        @foreach($resultado as $item)

        @if($item['estado']=='ok')
        @if($item['area']=='ok')
        <tr class="alert alert-success">
            @else
        <tr class="alert alert-warning">
            @endif
            @else
        <tr class="alert alert-danger">
            @endif



            <td>
                @if($item['estado']=='ok')
                <i class="bi bi-check-circle-fill" style="color:green;"></i>
                @else
                <i class="bi bi-x-square-fill" style="color:red;"></i>
                @endif
            </td>
            <td>{{$item['nro']}}</td>

            @if($item['trabajador']=='nd')
            <td><i class="bi bi-exclamation-diamond-fill"> </i>
                <small><b>Debe ingresar el nombre completo</b></small>
                <i class="bi bi-exclamation-diamond-fill"> </i>
            </td>


            @else
            <td>
                {{$item['trabajador']}}
            </td>
            @endif

            @if($item['RUN_formato']=='nd')
            <td colspan="3">
                <i class="bi bi-exclamation-diamond-fill"> </i>
                <small><b>Debe ingresar un R.U.N.</b></small>
                <i class="bi bi-exclamation-diamond-fill"> </i>
            </td>
            @else
            <td>
                @if($item['RUN_formato']=='ok' )
                <i class="bi bi-check-circle-fill" style="color:green;"></i>
                @else
                <i class="bi bi-x-square-fill" style="color:red;"></i>
                @endif
            </td>
            <td>
                @if($item['RUN_invalido']=='ok')
                <i class="bi bi-check-circle-fill" style="color:green;"></i>
                @else
                <i class="bi bi-x-square-fill" style="color:red;"></i>
                @endif


            </td>
            <td>
                @if($item['RUN_NU']=='ok')
                <i class="bi bi-check-circle-fill" style="color:green;"></i>
                @else
                <i class="bi bi-x-square-fill" style="color:red;"></i>
                @endif

            </td>
            @endif

            @if($item['movil']=='nd')
            <td colspan="2">
                <i class="bi bi-exclamation-diamond-fill"> </i>
                <small><b>Debe ingresar un nro. de móvil<b></small>
                <i class="bi bi-exclamation-diamond-fill"> </i>
            </td>
            @else

            <td>
                @if($item['movil']=='ok')
                <i class="bi bi-check-circle-fill" style="color:green;"></i>
                @else
                <i class="bi bi-x-square-fill" style="color:red;"></i>
                @endif


            </td>
            <td>
                @if($item['movil_NU']=='ok')
                <i class="bi bi-check-circle-fill" style="color:green;"></i>
                @else
                <i class="bi bi-x-square-fill" style="color:red;"></i>
                @endif

            </td>
            @endif

            @if($item['correo_NU']=='nd')
            <td>
                <i class="bi bi-exclamation-diamond-fill"> </i>
                <small><b>Debe ingresar un Correo</b></small>
                <i class="bi bi-exclamation-diamond-fill"> </i>
            </td>
            @else
            <td>
                @if($item['correo_NU']=='ok')
                <i class="bi bi-check-circle-fill" style="color:green;"></i>
                @else
                <i class="bi bi-x-square-fill" style="color:red;"></i>
                @endif

            </td>
            @endif

            <td>
                @if($item['area']=='ok')
                <i class="bi bi-check-circle-fill" style="color:green;"></i>
                @else
                <i class="bi bi-x-square-fill" style="color:yellow;"></i>
                @endif
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

    </div>
 
</div>




</div>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endsection