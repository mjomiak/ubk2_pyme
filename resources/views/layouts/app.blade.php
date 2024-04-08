<!doctype html>
<html lang="es-es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ubica2 pyme DEMO</title>

    <!--Fuentes
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

  
        < href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
            <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">-->
 <!--
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
   bootstrap Icons----------------------------------------------------------------------------------------------------------------------------------------
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    -->
    
    <!--Datatables----------------------------------------------------------------------------------------------------------------------------------------
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
   -->

<!--
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> 
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
-->





    @yield('cabecera')


    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    


</head>

<body>

    <div id="app">

        <nav class="d-flex" style="height: 70px; max-height: 70px;">
            <div class="col-2" style="background-color: #000;
             color:aliceblue;
             display: flex;
             align-items: center;
             justify-content: center; ">


            </div>
            <div class="col-8" style="background-color: #000;
            color:aliceblue;
             display: flex;
             align-items: center;
             justify-content: center; ">


                <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;  ">
                    <p style="font-size: 24px;line-height: 0.6;">Ubica2</p>
                    <p style="font-size: 12px;line-height: 0.6;">{{Session('nomCliente')}}</p>




                </div>







            </div>
            <div class="col-2" style="background-color: #000; 
            color:aliceblue;
            display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-end; padding: 5px;">
                <div>
                    <p style="font-size: 12px;line-height: 0.6; text-align:right;">
                        <a href="{{route('SigmaConfigUsuarios')}}" class="btn" style="color:aliceblue;border: 0px;padding: 0%;" title="Mi Perfil"> {{Session('NombreCompleto')}}</i></a> -
                        <a href="{{ route('logout') }}" class="btn" style="color:aliceblue;border: 0px;padding: 0%;" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" title="Cerrar Sesion"><i class="bi bi-box-arrow-right"></i></a>
                    </p>
                    @if(Session('NombreRol') && Session('NombreRol') !== null)
                    <p style="font-size: 12px;line-height: 0.6; text-align:right;">{{Session('NombreRol')}} - <a href="{{route('home')}}">Cambiar</a></p>
                    @endif
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>

            </div>


        </nav>


        @yield('content')

    </div>

<!-------------Aqui van los scripts------------------------------------------------------------------------------------------------------>
<!---jquery------------------------------------------------------------------------>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--Bootstrap----------------------------------------------------------------------------------------------------->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
<!--bootstrap Icons---------------------------------------------------------------------------------------------------------------------------------------->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  
     <!--Datatables---------------------------------------------------------------------------------------------------------------------------------------->
     <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>




<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

<!--fin Scripts -------------------------------------------------------------------------------------------------------------------------->
</body>

</html>