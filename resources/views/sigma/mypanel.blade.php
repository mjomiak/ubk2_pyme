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
  <div class="row"></div>

  <div class="row justify-content-center align-items-center">
    <div class="col-md-4">
<h5>Mi panel</h5>

<a href="{{route('SigmaUsuarioCambioPass')}}">Cambiar contraseña</a>
    </div>


  </div>



  @endsection