@extends('layouts.app')







@section('content')
{!! MigasFacade::render('ubk2/trab/create') !!}

<div class="row" style="margin-top: 1em;">
    <div class="col-2"></div>
<div class="col-8">

   @if(session('success'))
    <div id="msg_exito" class="alert alert-success">
        {!! session('success') !!}
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
        {!! session('error') !!}
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

   

    <form action="{{route('Ubk2UpdateTrab')}}" method="post">
        @csrf


<div class="card">
    <div class="card-header"><b>Actualizar Datos del Trabajador</b></div>
    <div class="card-body">

        <input type="hidden" id="id" name="id" value="{{$trabajador->id_trab}}">
        <div class="mb-3">
            <label for="nombreCompleto" class="form-label">Nombre Completo:</label>
            <input type="text" class="form-control @error('nombreCompleto') is-invalid @enderror" id="nombreCompleto"
                name="nombreCompleto" required autocomplete="off"
                placeholder="Ingrese el nombre completo del trabajador" value="{{$trabajador->nombreCompleto}}" style="text-transform: uppercase;">
            @error('nombreCompleto')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="rut" class="form-label">R.U.N.:</label>
            <input type="text" pattern="[0-9kK-.]*" class="form-control @error('rut') is-invalid @enderror" id="rut"
                name="rut" value="{{ number_format($trabajador->rut,0,',','.') }}-{{$trabajador->dv}}" required autocomplete="off">
            @error('rut')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>



        <div class="mb-3">
            <label for="movil" class="form-label">Móvil:</label>
            <input type="text" class="form-control @error('movil') is-invalid @enderror" id="movil" name="movil"
                value="{{ $trabajador->movil }}" required autocomplete="off">
            @error('movil')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        <div class="mb-3">
            <label for="movil" class="form-label">Correo:</label>
            <input type="text" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo"
                value="{{$trabajador->correo }}" required autocomplete="off">
            @error('correo')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="area" class="form-label">Área:</label>
            <select name="area" id="area" class="form-control @error('area') is-invalid @enderror" ">
          
                @foreach($areas as $a)
               @if($a->selected == 1)
                <option value="{{$a->id_area}}" selected >{{$a->nombre}}</option>
                @else
                <option value="{{$a->id_area}}"  >{{$a->nombre}}</option>
                @endif
                @endforeach
            </select>
            @error('area')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>



    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Enviar</button>

    </div>
   
   
</div>

    </form>
</div>

</div>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endsection