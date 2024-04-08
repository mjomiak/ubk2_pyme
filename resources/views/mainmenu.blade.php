@extends('layouts.app')

@section('content')

<div class="container-fluid" style="padding: 0px;">

    <div style="background-color: #e9ecef;">
    {!! MigasFacade::render('fake') !!}
    </div>
</div>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Menú Principal</div>

                <div class="card-body">

                    @if(isset($mensaje) && $mensaje != null)
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle">{{$mensaje}}</i> 
                    </div>
                    @endif

                   

               
                    <ul>

                        @if(isset($menu))
                        @foreach($menu as $levelOne)
                        @if($levelOne->cod_padre =="#")
                        @if($levelOne->ruta != "#")
                        <li><a href="{{route($levelOne->ruta)}}">{{$levelOne->nombre}}</a></li>
                        @else
                        <li>{{$levelOne->nombre}}</li>
                        @endif
                        <ul>
                            @foreach($menu as $levelTwo)
                            @if($levelTwo->cod_padre==$levelOne->cod_menu )
                            @if($levelTwo->ruta != "#")
                            <li> <a href="{{route($levelTwo->ruta)}}"> {{$levelTwo->nombre}}</a> </li>
                            @else
                            <li> {{$levelTwo->nombre}} </li>
                            @endif
                            <ul>
                                @foreach($menu as $levelThree)
                                @if($levelThree->cod_padre==$levelTwo->cod_menu)
                                @if($levelThree->ruta!= "#")
                                <li> <a href="{{route($levelThree->ruta)}}">{{$levelThree->nombre}}</a> </li>
                                @else
                                <li> {{$levelThree->nombre}}</li>
                                @endif
                                @endif
                                @endforeach
                            </ul>
                            @endif
                            @endforeach
                        </ul>
                        @endif
                        @endforeach
                        @endif
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endsection