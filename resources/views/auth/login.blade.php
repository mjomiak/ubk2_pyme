


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>..::Ubk2::Login::..</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
   
</head>

<body style="margin: 1%;">
    <style>
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr 2fr;
            grid-template-rows: repeat(4, 1fr);
            gap: 5px;
           
        }

        .grid-item {
            padding: 1px;
            /*border: 1px solid #ccc;*/
        }

        /* Estilo específico para la primera columna */
        .grid-item:first-child {
              border: 1px solid #ccc;
            grid-column: span 1;
            /* La primera columna abarca solo 1 columna */
            grid-row: span 4;
            /* La primera columna no tiene filas */
        }

        .center-container {
            /* height: 100vh; /* Establece la altura al 100% del viewport height (vh) */
            display: flex;
            justify-content: center;
            /* Centra horizontalmente */
            align-items: center;
            /* Centra verticalmente */
            text-align: center;
            /* Centra el texto horizontalmente */
        }

        .align-derecha {
            /* height: 100vh; /* Establece la altura al 100% del viewport height (vh) */
            display: flex;
            justify-content: right;
            /* Centra horizontalmente */
            align-items: center;
            /* Centra verticalmente */
            text-align: right;
            /* Centra el texto horizontalmente */
        }
        .align-center {
            /* height: 100vh; /* Establece la altura al 100% del viewport height (vh) */
            display: flex;
            justify-content: center;
            /* Centra horizontalmente */
            align-items: center;
            /* Centra verticalmente */
            text-align: center;
            /* Centra el texto horizontalmente */
        }
    </style>




<div class="row" style="text-align:right;">
    <div class="col-md-12" >
   
   
   
    <a href="#" >Acerca de</a>
</div>
</div>




<div class="row justify-content-center" style="margin-top: 10%">
   <br>




</div>
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card ">
            <div class="card-header">
                <h5> <i class="bi bi-box-arrow-in-right"></i> Acceso al sistema</h5>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="card-body">

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
  
    
                
                    @endif
                    <!-- prueba css grid-->
                    <div class="grid-container ">
                        <!-- Fila 1 -->
                        <div class="grid-item center-container">
                            <i style="font-size: 5rem"> <br></i><!-- poner el logo -->
                        </div>
                        <div class="grid-item" >
                          
                        </div>
                        <div class="grid-item">
                           

                        </div>


<!--fila 2-------------------------------------------------->

                        <div class="grid-item align-derecha">Correo:</div>
                        <div class="grid-item align-derecha">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                           
                            
                           
                        </div>

                        <!-- Fila 3 ------------------------------------ -->

                        <div class="grid-item align-derecha">Contraseña:</div>

                        <div class="grid-item align-derecha">

                            <div class="input-group ">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePasswordVisibility('password')">
                                    Mostrar/ocultar
                                </button>
                              
                            </div>
                        </div>




                        <!-- Fila 4 -->

                        <div class="grid-item align-center"> <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{
                                old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                Recordarme
                            </label>
                        </div></div>
                        <div class="grid-item align-derecha">
                            
                            @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                Olvidé mi contraseña
                            </a>
                            @endif
                            </div>

                    
                    </div>

                  

 
                    @csrf


            
                </div>
                <div class="card-footer align-derecha">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Acceder
                </div>

            </form>
        </div>


        <div class="text-muted align-derecha"><small> Ubica2 pyme v. 0.1 </small></div>
    </div>
</div>
</div>


<script>
    function togglePasswordVisibility(id) {
        var passwordInput = document.getElementById(id);
        passwordInput.type = (passwordInput.type === 'password') ? 'text' : 'password';
    }
</script>
</body>
</html>




<!--

<div class="input-group ">
    <input id="password" type="password"
        class="form-control @error('password') is-invalid @enderror" name="password" required
        autocomplete="current-password">
    <button class="btn btn-outline-secondary" type="button"
        onclick="togglePasswordVisibility('password')">
        Mostrar/ocultar
    </button>
    @error('password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>



-->