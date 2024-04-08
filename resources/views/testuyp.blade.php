@extends('layouts.app')

@section('content')

<div class="container-fluid" style="padding: 0px;">
    <div class="row">

    </div>
    <div class="row">
        <div class="col md-2"></div>
        <div class="col md-8">
            <hr>


            <form action="" method="post" >
                <label for="puerta">Puerta:</label>
            <input type="text" name="puerta" id="puerta" placeholder="ingrese puerta" class="form-control">
            <div class="row">
            <div class="col md-6">
                <label for="ruta">Ruta:</label>
                <input type="text" name="ruta" id="ruta" placeholder="Ingrese Ruta" class="form-control">
            </div>
            <div class="col md-6">
             <a href="#" onclick="" >Usar nombre
                puerta</a>
            </div>
</div>


                <label for="permiso">Permiso:</label>    
            <select name="permiso" name="permiso" class="form-control">
                <option value="1" selected>PERMITIDO</option>
                <option value="0">BLOQUEADO</option>
            </select>

            <label for="rol">Rol:</label>
            <select class="form-control" name="rol" id="rol">
                @foreach($roles as $rol)
                <option value="{{$rol->cod_rol}}">{{$rol->nombre}}</option>
                @endforeach
            </select>

            <label for="controlador">Controlador:</label>
            <select class="form-select" name="controlador" id="controlador">
                @foreach($archivos as $archivo)
                <option value="{{$archivo}}">{{$archivo}}</option>
                @endforeach
            </select>

            <label for="metodo">Metodo:</label>
            <input type="text" name="metodo" id="metodo" placeholder="ingrese metodo" class="form-control">

            <input type="submit" value="enviar">

        </form>
        </div>
        <div class="col md-2"></div>
    </div>
</div>
@endsection