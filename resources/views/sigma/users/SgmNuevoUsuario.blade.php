@extends('layouts.app')

@section('content')


    {!! MigasFacade::render('sys/usr/create') !!}

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
            <form method="POST" action="{{route('SigmaUsuarioGuardar')}}">
                @csrf

                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" autocomplete="off" 
                        class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" >
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                <div class="form-group">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre"  autocomplete="off" style="text-transform: uppercase;"
                        class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}">
                    @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="rol">Rol: </label>

                    <div class="text-info small">Seleccione a lo menos un rol, Ctrl+click para multiselección</div>




                    <select id="rol" name="rol[]" class="form-control @error('rol') is-invalid @enderror" multiple>

                        @foreach ($roles as $r)
                        <option value="{{ $r->id }}" @if(in_array($r->id, old('rol', []))) selected
                            @endif>{{$r->nombre}} - {{$r->descrip}}</option>

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






