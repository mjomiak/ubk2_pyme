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

{!! MigasFacade::render('ubk2/trn/update') !!}
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
            {{ session('error') }}
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

    <form action="{{route('Ubk2GuardarEdicionTurno')}}" method="post">
        @csrf

        <input type="hidden" name="id_turno" name="id_turno" value="{{$turno->id_turno}}">
        <input type="hidden" name="id_cliente" name="id_cliente" value="{{$turno->id_cliente}}">
       

        <div class="card">
            <div class="card-header"><b>Editar Turno</b></div>
            <div class="card-body">

            <div class="mb-3">
                    <label for="nombre_area" class="form-label">Área:</label>
                    <select name="id_area" id="id_area" class="form-control">
                        @foreach($areas as $a)
                            @if($a->selected == 1)
                            <option value="{{$a->id_area}}" selected> {{$a->nombre}}</option>
                            @endif
                            <option value="{{$a->id_area}}"> {{$a->nombre}}</option>
                        @endforeach
                    </select>
                    @error('cod_area')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                <div class="mb-3">
                    <label for="cod_turno" class="form-label">Código Turno:</label>
                    <input type="text" class="form-control @error('cod_turno') is-invalid @enderror" id="cod_turno" name="cod_turno" value="{{$turno->cod_turno}}" required autocomplete="off" placeholder="Ingrese el nombre del area" style="text-transform: uppercase;">
                    @error('cod_turno')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="descrip_area" class="form-label">Nombre Turno:</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ $turno->nombre}}" required placeholder="Ingrese una descripción breve" style="text-transform: uppercase;">
                    @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                <div class="mb-3">
                    <label for="inicio" class="form-label">Inicio:</label>
                    <input type="time"  class="form-control @error('inicio') is-invalid @enderror" id="inicio" name="inicio" value="{{$turno->inicio}}" placeholder="HH:mm" required>
                    @error('inicio')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="umbral_inicio" class="form-label">Umbral Entrada:</label>
                    <input type="number" min="0" max="60" class="form-control @error('umbral_inicio') is-invalid @enderror" id="umbral_inicio" name="umbral_inicio" value="{{ $turno->umbral_inicio }}" required>
                    @error('umbral_inicio')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label for="termino" class="form-label">Fin:</label>
                    <input type="time"  class="form-control @error('termino') is-invalid @enderror" id="termino" name="termino" value="{{$turno->termino}}" required>
                    @error('termino')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label for="umbral_termino" class="form-label">Umbral Salida:</label>
                    <input type="number"  min="0" max="60" class="form-control @error('umbral_termino') is-invalid @enderror" id="umbral_termino" name="umbral_termino" value="{{ $turno->umbral_termino }}" required>
                    @error('umbral_termino')
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




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>


<script>
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
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









<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

@endsection