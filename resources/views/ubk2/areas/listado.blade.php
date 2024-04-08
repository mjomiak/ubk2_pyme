@extends('layouts.app')

@section('content')


<style>
    .container {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
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
</style>

{!! MigasFacade::render('ubk2/ara/read') !!}



<div class="container-fluid">

    @include('parciales.modales')




    <div class="card">
        <div class="card-header">
            <div class="grid_header">
                <div class="item left-column">

                    <h5>Listado de Areas </h5>
                </div>
                <div class="item right-column">
                    @if ($rutas['c']=='#')
                    <a class="text-muted" class="btn btn-sm btn-secondary"><i class="bi bi-person-fill-add"></i> Nueva</a>
                    @else
                    <a href="{{route($rutas['c'])}}" title="Crea un nuevo usuario en el sistema." class="btn btn-sm btn-success"><i class="bi bi-person-fill-add"></i> Nueva </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">


            <table id="tbl_areas">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Nombre</td>
                        <td>Descripción</td>
                        <td>Encargado</td>
                        <td>Acciones</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($areas as $a)
                    <tr @if($a->activo==0)style="background-color: #a6acaf;"@endif>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$a->nombre}} </td>
                        <td>{{$a->descrip}}</td>
                        <td>{{$a->encargado}} </td>
                        <td>
                        <div class="container">
                        <div class="item">
                            @if ($rutas['u']=='#')
                            <a class="text-muted"><i class="bi bi-pencil-square"></i> Editar</a>
                            @else
                            <a class="btn btn-sm btn-primary" href="{{route('Ubk2EditarArea', ['id' => $a->id_area])}}"><i class="bi bi-pencil-square"></i> Editar </a>
                            @endif
                        </div>
                            <div class="item">
                                @if ($rutas['l']=='#') <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="No tiene permiso para realizar esta acción" onclick="noPermiso()">@if($a->activo) <i class="bi bi-lock-fill"></i> Bloquear @else <i class="bi bi-unlock-fill"></i> Desbloquear
                                    @endif</button>
                                @else
                                <button type="button" class="btn btn-sm btn-warning" onclick="abrirModal('{{$a->id_area}}','{{route($rutas['l'])}}')">@if($a->activo)<i class="bi bi-lock-fill"></i> Bloquear
                                    @else <i class="bi bi-unlock-fill"></i> Desbloquear @endif
                                </button>
                                @endif
                                </div>
                                <div class="item">
                                    @if ($rutas['d']=='#')
                                    <button type="button" class="btn  btn-sm btn-secondary" onclick="noPermiso()"><i class="bi bi-trash-fill"></i>Borrar</button>
                                    @else
                                    <button type="button" class="btn btn-sm btn-danger" onclick="abrirModal('{{$a->id_area}}','{{route($rutas['d'])}}')"><i class="bi bi-trash-fill"></i> Borrar</button>
                                    @endif
                                </div>
                                </div>
                        </td>





                    </tr>
                    @endforeach
                </tbody>
            </table>




        </div>
    </div>
</div>







<!--------------------------------------------------------------------->




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('#tbl_areas').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json" // Ruta al archivo de idioma
            },
            "columnDefs": [{
                    "width": "20px",
                    "targets": 0
                },

            ]
        });
    });
</script>


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endsection