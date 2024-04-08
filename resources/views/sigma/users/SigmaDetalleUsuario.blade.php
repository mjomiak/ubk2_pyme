@extends('layouts.app')

@section('content')

  {!! MigasFacade::render('sys/usr/detail') !!}


<div class="container-fluid">


    {{-- -------------------------------------------------------------------------------------------}}

    <div class="row" style="margin-top: 1em;">
        <div class="col-1"></div>
        <div class="col-10">



            @if(isset($success))
            <div id="msg_exito" class="alert alert-success">
                {!! $success !!}
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

            @if(isset($error))
            <div id="msg_error" class="alert alert-danger">
                {!! $error !!}
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



        </div>
    </div>




    {{----------------------------------------------------------------------------------------------}}

<style>

.contenedor {
    display: grid; /* Establece un contenedor de cuadrícula */
    grid-template-columns: 1fr 1fr; /* Divide el contenedor en dos columnas iguales */
    gap: 20px; /* Espacio entre las columnas */
}

.columna {
    padding: 20px;
    border: 1px solid #ccc;
}
</style>


    <div class="card">
        <div class="card-header">
           <h5><i class="bi bi-search"></i> Detalle de Usuario </h5>
        </div>
        <div class="card-body">

            <div class="contenedor">
                <div class="columna">

                    <h5><u>Datos Generales</u></h5>
                    <b>Nombre:</b> {{$user->name}}<br>
                    <b>E-Mail:</b> {{$user->email}}<br>
                    <b>Activo:</b> @if($user->activo==1)Sí @else No @endif<br>
                    <b>Fecha de alta:</b> {{$user->created_at}}<br>
                    <b>Fecha Actualización:</b> {{$user->updated_at}}<br>
                    <b>Fecha Última sesión:</b> {{$us}}<br>
               

                </div>
                <div class="columna">
                    <h5><u>Perfiles Habilitados</u></h5><br>
                    @foreach($roles as $r)
                    {{$r}}<br>
                    @endforeach
                </div>
            </div>



          
            

<hr>
<h5><u>Actividades últimos 30 días</u></h5>  <button class="btn btn-sm btn-success" onclick="descargarContenido()"> <i class="bi bi-file-earmark-arrow-down"></i> Descargar</button>
<textarea class="form-control" style="height: 10rem;" id="miTextarea">
@foreach($log as $l)
{{$l}}
@endforeach
</textarea>

               
        </div>
<div class="card-footer">
    <div class="d-flex justify-content-end">
        <a href="{{route('SigmaListadoUsuarios')}}" class="btn btn-success"> <i class="bi bi-backspace"></i> Atrás</a>

    </div>
</div>
    </div>
</div>


<script>
    function descargarContenido() {
      // Obtener el contenido del textarea
      var contenido = document.getElementById('miTextarea').value;

      // Crear un Blob con el contenido
      var blob = new Blob([contenido], { type: 'text/plain' });

      // Crear un enlace de descarga
      var enlaceDescarga = document.createElement('a');
      enlaceDescarga.href = URL.createObjectURL(blob);
      enlaceDescarga.download = 'LogActividades.txt';

      // Agregar el enlace al documento y hacer clic en él
      document.body.appendChild(enlaceDescarga);
      enlaceDescarga.click();

      // Eliminar el enlace del documento
      document.body.removeChild(enlaceDescarga);
    }
  </script>




<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endsection