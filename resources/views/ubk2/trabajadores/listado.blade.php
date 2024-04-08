@extends('layouts.app')

@section('content')
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
</style>
{!! MigasFacade::render('ubk2/trab/read') !!}

<div class="row" style="margin-top: 1em;">
    <div class="col-1"></div>
    <div class="col-10">





        @if(session('success'))
        <div id="msg_exito" class="alert alert-success">
            {!! session('success') !!}
        </div>

        <script>
            // Mostrar el div inicialmente
            document.getElementById('msg_exito').style.display = 'block';

            // Ocultar el div después de 5 segundos con un efecto de desvanecimiento
            setTimeout(function() {
                var miDiv = document.getElementById('msg_exito');
                miDiv.style.transition = 'opacity 1s ease-in-out';
                miDiv.style.opacity = '0';

                // Puedes agregar más lógica después de que se haya completado el desvanecimiento
                setTimeout(function() {
                    miDiv.style.display = 'none';
                }, 5000); // 1000 ms = 1 segundo (corresponde a la duración de la transición)
            }, 1000); // 5000 ms = 5 segundos
        </script>



        @endif

        @if(session('error'))
        <div id="msg_error" class="alert alert-danger">
            {!! session('error') !!}
        </div>
        <script>
            // Mostrar el div inicialmente
            document.getElementById('msg_error').style.display = 'block';

            // Ocultar el div después de 5 segundos con un efecto de desvanecimiento
            setTimeout(function() {
                var miDiv = document.getElementById('msg_error');
                miDiv.style.transition = 'opacity 1s ease-in-out';
                miDiv.style.opacity = '0';

                // Puedes agregar más lógica después de que se haya completado el desvanecimiento
                setTimeout(function() {
                    miDiv.style.display = 'none';
                }, 1000); // 1000 ms = 1 segundo (corresponde a la duración de la transición)
            }, 5000); // 5000 ms = 5 segundos
        </script>

        @endif

        <div class="card">
            <div class="card-header">
                <div class="grid_header">
                    <div class="item left-column">
                        <h5>Listado de Trabajadores </h5>
                    </div>
                    <div class="item right-column">
                        <div class="btn-group" role="group">
                            <a class="btn btn-sm btn-success" href="{{route($rutas['c'])}}" title="Crea un nuevo Trabajador (Usuario App)">Nuevo</a> <br>
                            <a class="btn btn-sm btn-primary" href="{{route($rutas['ld'])}}" title="Permite la carga masiva desde una planilla de cálculo">Carga Masiva</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <table id="listado">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>R.U.N.</th>
                            <th>Nombre Completo</th>
                            <th>Correo</th>
                            <th>Móvil</th>
                            <th>Área Asoc.</th>
                            <th>Opciones</th>

                        </tr>
                    </thead>
                    <tbody>

                        @foreach($trabajadores as $t)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$t->rut}}-{{$t->dv}}</td>
                            <td>{{$t->nombreCompleto}}</td>
                            <td>{{$t->correo}}</td>
                            <td>{{$t->movil}}</td>
                            <td>{{$t->area}}</td>
                            <td> <a class="btn btn-sm btn-primary" href="{{route($rutas['u'],['id'=>$t->id_trab])}}">Modificar</a>

                                <a class="btn btn-sm btn-warning" href="{{route($rutas['d'],['id'=>$t->id_trab])}}">Baja</a>
                                <a class="btn btn-sm btn-danger" href="{{route($rutas['rd'],['id'=>$t->id_trab])}}">Elim. tot</a>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#listado').DataTable({
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








@endsection