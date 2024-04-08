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

  {!! MigasFacade::render('sys/usr/read') !!}



<div class="container-fluid">

@include('parciales.modales')





    <div class="card">
        <div class="card-header">


            <div class="grid_header">
                <div class="item left-column">
                    
                    <h5>Listado de Usuarios </h5>
                </div>
                <div class="item right-column">
                    @if ($rutas['c']=='#') <a class="text-muted"  class="btn btn-sm btn-secondary"><i class="bi bi-person-fill-add"></i> Nuevo</a> @else<a href="{{route($rutas['c'])}}"
                        title="Crea un nuevo usuario en el sistema." class="btn btn-sm btn-success"><i class="bi bi-person-fill-add"></i> Nuevo </a> @endif
                </div>
            </div>




        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="listado">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Activo</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr style="margin: 0%; padding: 0%;">

                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>@if($usuario->activo==1)Sí @else No @endif</td>
                        <td style="margin: 0%; padding: 0%;">
                            <div class="container">
                                {{--Detalles---------------------------------------------------------------------------------------------------}}
                                <div class="item">
                                    @if ($rutas['dt']=='#')
                                    <a class=" btn btn-sm btn-secondary"><i class="bi bi-search"></i> Detalles</a>
                                    @else
                                    <a class="btn btn-sm btn-primary"
                                        href="{{route($rutas['dt'],['id'=>$usuario->id])}}"><i class="bi bi-search"></i> Detalles</a>
                                    @endif
                                </div>
                                {{--editar---------------------------------------------------------------------------------------------------}}
                                <div class="item">
                                    @if ($rutas['u']=='#')
                                    <a class="text-muted"><i class="bi bi-pencil-square"></i> Editar</a>
                                     @else
                                     <a class="btn btn-sm btn-primary" href="{{route($rutas['u'],['id'=>$usuario->id])}}"><i class="bi bi-pencil-square"></i> Editar </a>
                                    @endif
                                </div>


                                {{--Reset_de_contraseña-------------------------------------------------------------------------------------}}

                                <div class="item">
                                    @if ($rutas['pr']=='#')
                                    <a class="btn btn-sm btn-secondary"><i class="bi bi-arrow-repeat"></i> Rst. Pass</a>
                                    @else
                                    <a class="btn btn-sm btn-primary"
                                        href="{{route($rutas['pr'],['id'=>$usuario->id])}}"><i class="bi bi-arrow-repeat"></i> Rst. Pass</a>
                                    @endif
                                </div>



                                {{--forzar_cambio_contraseña------------------------------------------------------------------------------------}}

                                <div class="item">
                                    @if ($rutas['fpr']=='#')
                                    <a class="btn btn-sm btn-secondary"><i class="bi bi-recycle"></i> Forzar Cambio Pass</a>
                                    @else
                                    <a class="btn btn-sm btn-primary"
                                        href="{{route($rutas['fpr'],['id'=>$usuario->id])}}"><i class="bi bi-recycle"></i> Forzar Rst Pass</a>
                                    @endif
                                </div>





                                {{--bloquear---------------------------------------------------------------------------------------------------}}
                                {{--
                                <div class="item">

                                    @if ($rutas['l']=='#')<a class="text-muted">Bloquear</a> - @else<a
                                        href="{{route($rutas['l'],['id'=>$usuario->id])}}">Bloquear </a> - @endif
                                </div>
                                --}}


                                {{--Habilitar_Deshabilitar-----------------------------------------------------------------------------------------------}}
                                <div class="item">
                                    @if ($rutas['dh']=='#') <button type="button" class="btn btn-sm btn-secondary"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="No tiene permiso para realizar esta acción"
                                        onclick="noPermiso()">@if($usuario->activo) <i class="bi bi-lock-fill"></i> Deshabilitar @else <i class="bi bi-unlock-fill"></i> Habilitar
                                        @endif</button>
                                    @else
                                    <button type="button" class="btn btn-sm btn-warning"
                                        onclick="abrirModal('{{$usuario->id}}','{{route($rutas['dh'])}}')">@if($usuario->activo)<i class="bi bi-lock-fill"></i> Deshabilitar
                                        @else <i class="bi bi-unlock-fill"></i> Habilitar @endif
                                    </button>
 
                                    @endif
                                </div>
                                {{--Borrado_logico-------------------------------------------------------------------------------------------------------------}}
                                <div class="item">
                                    @if ($rutas['dt']=='#')
                                    <button type="button" class="btn  btn-sm btn-secondary"
                                        onclick="noPermiso()"><i class="bi bi-trash-fill"></i>Borrar</button>
                                    @else
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="abrirModal('{{$usuario->id}}','{{route($rutas['d'])}}')"><i class="bi bi-trash-fill"></i> Borrar</button>
                                    @endif
                                </div>
                                {{--Borrado_Definitivo--------------------------------------------------------------------------------------------------------------}}
                                <div class="item">
                                    @if ($rutas['rd']=='#') <button type="button" class="btn btn-sm btn-secondary"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="No tiene permiso para realizar esta acción" onclick="noPermiso()"><i class="bi bi-eraser"></i> Borrado
                                        Def.</button> @else
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="abrirModal('{{$usuario->id}}','{{route($rutas['rd'])}}')"><i class="bi bi-eraser"></i> Borrado
                                        Def.</button>
                                    @endif
                                </div>
                            </div> <!--container-->
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
    $(document).ready(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('#listado').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json" // Ruta al archivo de idioma
            },
            "columnDefs": [
                { "width": "20px", "targets": 0 },

            ]
        });
    });
</script>


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endsection