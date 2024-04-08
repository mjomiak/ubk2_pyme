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

{!! MigasFacade::render('ubk2/trab/load') !!}
<div class="row" style="margin-top: 1em;">
   
    <div class="col-2"></div>
    <div class="col-8">
  


    @if(session('success'))
    <div id="msg_exito" class="alert alert-success">
        {{ session('success') }}
    </div>

    <script>
        // Mostrar el div inicialmente
        document.getElementById('msg_exito').style.display = 'block';

        // Ocultar el div después de 5 segundos con un efecto de desvanecimiento
        setTimeout(function () {
            var miDiv = document.getElementById('msg_exito');
            miDiv.style.transition = 'opacity 1s ease-in-out';
            miDiv.style.opacity = '0';

            // Puedes agregar más lógica después de que se haya completado el desvanecimiento
            setTimeout(function () {
                miDiv.style.display = 'none';
            }, 5000); // 1000 ms = 1 segundo (corresponde a la duración de la transición)
        }, 1000); // 5000 ms = 5 segundos
    </script>



    @endif

    @if(session('error'))
    <div id="msg_error" class="alert alert-danger">
        {{ session('error') }}
    </div>
    <script>
        // Mostrar el div inicialmente
        document.getElementById('msg_error').style.display = 'block';

        // Ocultar el div después de 5 segundos con un efecto de desvanecimiento
        setTimeout(function () {
            var miDiv = document.getElementById('msg_error');
            miDiv.style.transition = 'opacity 1s ease-in-out';
            miDiv.style.opacity = '0';

            // Puedes agregar más lógica después de que se haya completado el desvanecimiento
            setTimeout(function () {
                miDiv.style.display = 'none';
            }, 1000); // 1000 ms = 1 segundo (corresponde a la duración de la transición)
        }, 5000); // 5000 ms = 5 segundos
    </script>

    @endif
    {{--
    @if ($errors->any())
    <div>
        <strong>Error de Validación:</strong>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    --}}

  
  <div class="card md-8">
    <div class="card-header">
     <h5>Carga Masiva de trabajadores</h5>
    </div>
    <div class="card-body">
     
      <p class="card-text">Para generar la garga masiva de trabajadores, descargue la plantilla <a href="{{route('ExportCMtrab')}}">aquí</a>, editela, una vez lista, haga click en examinar,
       seleccionele y finalmente pulse el biton cargar</p>
       <div class="alert alert-info" role="alert">
  <strong>Importante:</strong> Los datos cargados mediante la planilla se ANEXARAN a los ya existentes, si el <b>RUT</b> o <b>Teléfono Movil</b> del trabajador se repite, no se agregara el registro, quedando en el sistema el previamente registrado.
</div>

<form action="{{route('ImportCMtrab')}}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">
    <button type="submit">Importar</button>
</form>
    
    </div>
  </div>

</div>

</div>




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