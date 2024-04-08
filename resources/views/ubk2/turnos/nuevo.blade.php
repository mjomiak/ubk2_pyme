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

{!! MigasFacade::render('ubk2/trn/create') !!}


<div class="container-fluid">



    <div class="row" style="margin-top: 1em;">

        <div class="col-2"></div>
        <div class="col-8">



            <form action="{{route('Ubk2GuardarTurno')}}" method="post">
                @csrf


                <div class="card">
                    <div class="card-header"><b>Nuevo Turno</b></div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label for="nombre_area" class="form-label">Área:</label>
                            <select name="id_area" id="id_area" class="form-control">
                                <option value="-1" selected> SELECCIONE UN ÁREA</option>
                                @foreach($areas as $a)
                                <option value="{{$a->id_area}}"> {{$a->nombre}}</option>
                                @endforeach
                            </select>
                            @error('cod_area')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>



                        <div class="mb-3">
                            <label for="cod_turno" class="form-label">Código Turno:</label>
                            <input type="text" class="form-control @error('cod_turno') is-invalid @enderror" id="cod_turno" name="cod_turno" value="{{old('cod_turno')}}" required autocomplete="off" placeholder="Ingrese un código para el turno" style="text-transform: uppercase;">
                            @error('cod_turno')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descrip_area" class="form-label">Nombre Turno:</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre')}}" required placeholder="Ingrese un nombre para el turno" style="text-transform: uppercase;">
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>



                        <div class="mb-3">
                            <label for="inicio" class="form-label">Inicio:</label>
                            <input type="time" class="form-control @error('inicio') is-invalid @enderror" id="inicio" name="inicio" value="{{old('inicio')}}" required>
                            @error('inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="umbral_inicio" class="form-label">Umbral Entrada:</label>
                            <input type="number" min="0" max="60" class="form-control @error('umbral_inicio') is-invalid @enderror" id="umbral_inicio" name="umbral_inicio" value="{{old('umbral_inicio')}}" required>
                            @error('umbral_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="termino" class="form-label">Fin:</label>
                            <input type="time" class="form-control @error('termino') is-invalid @enderror" id="termino" name="termino" value="{{old('termino')}}" required>
                            @error('termino')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="umbral_termino" class="form-label">Umbral Salida:</label>
                            <input type="number" min="0" max="60" class="form-control @error('umbral_termino') is-invalid @enderror" id="umbral_termino" name="umbral_termino" value="{{ old('umbral_termino') }}" required>
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
</div>




@endsection