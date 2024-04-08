@extends('layouts.app')

@section('content')
<style>
    .campo {
        padding: 0px;
        border: 0px;
        display: grid;
        grid-template-columns: 1fr 3fr;
        gap: 1%;
    }

    .etiqueta-campo {
        display: flex;
        padding: 0px;
        border: 0px;
        align-items: center;
        /* Centra verticalmente */
        justify-content: flex-end;
        /* Alinea a la derecha horizontalmente */
        /* border: 1px solid #ccc;*/
        box-sizing: border-box;
    }

    /* Estilos adicionales según tus necesidades */
    .control-campo {
        padding: 0px;
        border: 0px;
        /* border: 1px solid #ccc;*/
        align-items: center;
        /* Centra verticalmente */
        justify-content: flex-end;
        /* Alinea a la derecha horizontalmente */
        box-sizing: border-box;
        /* Ajusta el espaciado según sea necesario */
    }

    /* Estilos de ejemplo para resaltar la estructura */
</style>


    {!! MigasFacade::render('sys/usr/update') !!}

<div class="container-fluid">

    {{--
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

 --}}


<div class="row justify-content-center">
    <div class="col-8">
<!----->

<form method="POST" action="{{route('SigmaUsuarioGuardarEdicion')}}">
<div class="card">
    <div class="card-header">
        <h5><i class="bi bi-pencil-square"></i> Editar Usuario</h5>

    </div>
    <div class="card-body">
       
            @csrf

            <input type="hidden" id="id" name="id" value="{{$user->id}}">


            <div class="campo">
                <div class="etiqueta-campo">Correo Electrónico:</div>
                <div class="control-campo">
                    <input type="email" id="email" name="email" autocomplete="off"
                        class="form-control @error('email') is-invalid @enderror" value="{{$user->email }}">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="campo">
                <div class="etiqueta-campo">Nombre Completo:</div>
                <div class="control-campo">
                    <input type="text" id="nombre" name="nombre" autocomplete="off"
                        style="text-transform: uppercase;"
                        class="form-control @error('nombre') is-invalid @enderror" value="{{ $user->name }}">
                    @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="alert alert-info " style="margin: 1%;"><b>Importante:</b> A continuación se muestran los
                roles asignados al usuario,
                si no desea agregar o quitar roles simplemente presione guardar o cancelar, caso contrario considere:
                <ul>
                    <li>El listado es multiselección puede seleccionar mas de un item con ctrl + click.</li>
                    <li>Al guardar se actualizaran los roles según la selección.</li>
                    <li>El usuario quedará unicamente con los roles seleccionados(se quitan los no seleccionados).</li>
                    <li>A modo de ayuda se resalta en verde y mayúscula los roles actuales del usuario a modificar.</li>
                </ul>
            </div>

            <div class="campo">
                <div class="etiqueta-campo">Roles:</div>
                <div class="control-campo">
                    <select id="rol" name="rol[]" class="form-control @error('rol') is-invalid @enderror" multiple>
                        @foreach ($roles as $r)
                        <option value="{{ $r->id }}" @if(in_array($r->id, $id_roles)) selected style="color:green;
                            text-transform:uppercase;"
                            @endif> {{$r->nombre}} - {{$r->descrip}}</option>
                        @endforeach
                    </select>
                    @error('rol')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>








         
 



    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-end">
            <a href="{{route('SigmaListadoUsuarios')}}"  class="btn btn-warning"><i class="bi bi-x-circle"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Guardar</button>
        </div>
     
    </div>
</div>
</form>


<!----->
    </div>
</div>


   
</div>

<!-- Agregamos los scripts de jQuery y DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('.datatable').DataTable();
    });
</script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endsection