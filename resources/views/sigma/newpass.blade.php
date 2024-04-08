@extends('layouts.app')

@section('content')

<style>
  .grid-container {
    display: grid;
    grid-template-columns: 1fr 2fr;
    grid-template-rows: repeat(2, 1fr);
    gap: 5px;

  }

  .grid-item {
    padding: 1px;
    /*border: 1px solid #ccc;*/
  }

  /* Estilo específico para la primera columna */
  /*.grid-item:first-child {
        border: 1px solid #ccc;
      grid-column: span 1;
      /* La primera columna abarca solo 1 columna */
  /*  grid-row: span 4;
      /* La primera columna no tiene filas */
  /*}*/

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


<div class="container-fluid">
  <div class="row"></div><br>
</div>
<div class="row justify-content-center align-items-center">
  <div class="col-md-4">

    <form action="{{route('SigmaUsuarioSavePass')}}" method="post">
      @csrf
      <div class="card">
        <div class="card-header">
          <h5><i class="bi bi-recycle"></i> Cambio de Contraseña</h5>
        </div>
        <div class="card-body">
          <div class="grid-container">
            <div class="grid-item">Nueva Contraseña:</div>
            <div class="grid-item">
              <div class="input-group ">
                <input id="pass1" type="password" class="form-control @error('pass1') is-invalid @enderror"
                  name="pass1" required autocomplete="current-password">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('pass1')">
                  Mostrar/ocultar
                </button>
              </div>
            </div>
              <div class="grid-item">Repita Contraseña:</div>
              <div class="grid-item">
                <div class="input-group ">
                  <input id="pass2" type="password" class="form-control @error('pass2') is-invalid @enderror"
                    name="pass2" required autocomplete="current-password">
                  <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('pass2')">
                    Mostrar/ocultar
                  </button>
                </div>
              </div>
            </div>
            </div>
            <div class="card-footer ">
              <div class="align-derecha">
                <button type="submit" class="btn  btn-success"><i class="bi bi-floppy"></i> Guardar</button>
              </div>

            </div>
          </div>
    </form>
  </div>
</div>
</div>



<script>
  function togglePasswordVisibility(id) {
    var passwordInput = document.getElementById(id);
    passwordInput.type = (passwordInput.type === 'password') ? 'text' : 'password';
  }
</script>

@endsection