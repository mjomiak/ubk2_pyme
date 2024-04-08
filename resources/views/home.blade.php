@extends('layouts.app')

@section('content')

{!! MigasFacade::render('fake') !!}

</div>
<div>

    <style>
        .datagrid {
            display: flex;
            border: 1px solid #ccc;
        }

        .columna {
            padding: 10px;
            border-right: 1px solid #ccc;
            text-align: center;
            /*height: 100vh;*/
        }

        .columna:last-child {
            border-right: none;
        }

        ul,
        ol {
            list-style-type: none;
        }

        .contenedor-flex {
            display: flex;
        }

        .columna {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .fila {
            display: flex;
        }

        .celda {
            flex: 1;
            border: 1px solid #ccc;
        }

        /* Opcional: Estilo para que los gráficos se ajusten dentro de las celdas */
        .celda canvas {
            width: 50%;
            max-width: 100%;
            height: auto;
        }
    </style>
    <div class="datagrid">
        <!-- menu ---------------------------------------------------------------------------------->
        <div class="columna" style="flex: 2;">
            <h5>Menú Principal</h5>
            <hr>
            <div style="text-align: left;">

                <ul>

                    @if(isset($menu))
                    @foreach($menu as $levelOne)
                    @if($levelOne->cod_padre =="#")
                    @if($levelOne->ruta != "#")
                    {{-- <li><a href="{{route($levelOne->ruta)}}">{{$levelOne->nombre}}</a></li>--}}
                    @else
                    {{-- <li>{{$levelOne->nombre}}</li> --}}
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
        <!-- dash -------------------------------------------------------------------------------------->
        <div class="columna" style="flex: 10;">
            <h5>Dashboard</h5>
            <hr>
            <div class="fila">
                <div class="celda"></div>
                <div class="celda">
                    <!-- Gráfico 1 -->
                    <canvas id="grafico1" width="100" height="100"></canvas>
                </div>
                <div class="celda">
                    <!-- Gráfico 2 -->
                    <canvas id="grafico2" width="100" height="100"></canvas>
                </div>
                <div class="celda">
                    <!-- Gráfico 2 -->
                    <canvas id="graficox" width="100" height="100"></canvas>
                </div>
                <div class="celda"></div>
            </div>
            <div class="fila">
                <div class="celda"></div>
                <div class="celda"> <canvas id="grafico3" width="200" height="200"></canvas></div>
                <div class="celda"> <canvas id="grafico5" width="200" height="200"></canvas></div>
                <div class="celda" id="mapa">
                    
                </div>
                <div class="celda"></div>
            </div>
        </div>

    </div>

    <!-- fin dash ---------------------------------------------------------------------------------->
</div> <!--???-->



@if(isset($mensaje) && $mensaje != null)
<div class="alert alert-info" role="alert">
    <i class="bi bi-info-circle">{{$mensaje}}</i>
</div>
@endif

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datos para el primer gráfico
        var data1 = {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo'],
            datasets: [{
                label: 'Ventas Mensuales',
                data: [12, 19, 3, 5, 2],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };

        // Datos para el segundo gráfico
        var data2 = {
            labels: ['A', 'B', 'C', 'D', 'E'],
            datasets: [{
                label: 'Ejemplo',
                data: [20, 10, 15, 30, 25],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        // Configuración para ambos gráficos
        var options = {
            scales: {

                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Título Común',
                }
            }
        };

        // Inicializar el primer gráfico
        var ctx1 = document.getElementById('grafico1').getContext('2d');
        var myChart1 = new Chart(ctx1, {
            type: 'bar',
            data: data1,
            options: options
        });

        // Inicializar el segundo gráfico
        var ctx2 = document.getElementById('grafico2').getContext('2d');
        var myChart2 = new Chart(ctx2, {
            type: 'bar',
            data: data2,
            options: options
        });


        // Inicializar el segundo gráfico
        var ctx3 = document.getElementById('grafico3').getContext('2d');
        var myChart3 = new Chart(ctx3, {
            type: 'pie',
            data: data2,
            options: options
        });

        var mapa = L.map('mapa').setView([51.505, -0.09], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapa);
    });
</script>


@endsection