@extends('layouts.app')

@section('content')

<div class="container-fluid" style="padding: 0px;">

    <div style="background-color: #e9ecef;">

        {{ Breadcrumbs::render('SigmaListadoUsuarios') }}
    </div>
</div>
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
    {{-- var_dump(old()) --}}
    <div class="card">
        <div class="card-header">
            <h5>Nuevo Usuario </h5>

        </div>
        <div class="card-body">
            <form method="POST" action="{{route('SigmaRolGuardar')}}">
                @csrf

              


                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre"  autocomplete="off" 
                        class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}">
                    @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="cod">Código:</label>
                    <input type="text" id="cod" name="cod"  autocomplete="off" 
                        class="form-control @error('cod') is-invalid @enderror" value="{{ old('cod') }}">
                    @error('cod')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descrip|">Descripción:</label>
                    <input type="text" id="descrip" name="descrip"  autocomplete="off" 
                        class="form-control @error('descrip') is-invalid @enderror" value="{{ old('descrip') }}">
                    @error('descrip')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>




                <div class="form-group">
                    <label for="rol">Basado en: </label>

                    <div class="alert alert-info">Seleccione un rol como base, esto hará que el nuevo rol tenga todas las funcionalidades del seleccionado,
                        sin embargo, estarán deshabilitadas, para completar el perfil, deberá editar los permisos correpondientes.</div>




                    <select id="rol" name="rol" class="form-control @error('rol') is-invalid @enderror" >
<option value="-1">Seleccione un rol base</option>
                        @foreach ($roles as $r)
                   

                        <option value="{{ $r->id }}" {{ old('rol') == $r->id ? 'selected' : '' }}>
                            {{ $r->nombre }} - {{$r->descrip}}
                        </option>
                          

                        @endforeach




                    </select>
                    @error('rol')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>




                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Guardar</button>
            </form>



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