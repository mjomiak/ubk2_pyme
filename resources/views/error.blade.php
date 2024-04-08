@extends('layouts.app')

@section('content')

<div class="container-fluid">
<div class="row" style="margin: 3%;">

</div>
<div class="row justify-content-center">




<div class="col-md-6">
    
    
    
    
    <h1> Se ha producido un error:</h1>
    <hr>
    <div class="alert alert-warning">
        @if(isset($error))
        {{$error}}
        @endif
    </div>
<hr>

    <a href="{{route('home',['nav'=>1])}}" class="btn btn-primary">Entendido</a>
</div>
</div>
</div>




@endsection