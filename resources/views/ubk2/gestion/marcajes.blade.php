@extends('layouts.app')

@section('content')

<div class="row" style="margin-top: 1em;">
    <div class="col-1"></div>
    <div class="col-10">



    <div>Marcaciones del trabajador {{$trab->nombreCompleto}}</div>


<table class="table">



@foreach($marcaciones as $m)
<tr>
    <td>{{$m->date_reg}}</td>
    <td>{{$m->time_reg}}</td>
    <td>{{$m->type_reg}}</td>
    <td><a href="https://maps.google.com/?q={{$m->latitud}},{{$m->longitud}}" target="_blank">Ubicación</a> </td>
</tr>


@endforeach

</table>
    </div>
</div>

@endsection