@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="margin: 2%;"></div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h5><i class="bi bi-hand-index-thumb"></i> Seleccione rol de trabajo</h5> </div>
                <div class="card-body">

@if(isset($mensaje) && $mensaje != null)
<div class="alert alert-info" role="alert">
  <i class="bi bi-info-circle"></i> {{$mensaje}}
</div>


@endif

                @foreach($perfiles as $perfil)





<a href="{{ route('SelectRol', ['cod_rol' => $perfil->cod_rol,'_token' => csrf_token()]) }}">{{$perfil->nombre}} - {{$perfil->descrip}}</a><br>

@endforeach

                </div>

             
            </div>
        </div>
    </div>
</div>
@endsection
