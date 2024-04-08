

    @extends('layouts.sincabecera')

    @section('content')


    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif




    <form action="{{route('tstSaveMarcaje')}}" method="post">
@csrf

<table width="50%"  class="table table-bordered">
    <tr>
        <td colspan="2">Marcacion de prueba  </td>
      
    </tr>
    <tr>
        <td width="100px">
trabajador (trabajador)
        </td>
        <td>

            <select name="trabajador" id="trabajador">
                @foreach($trabas as $t)
                <option value="{{$t->id}}">{{$t->nombreCompleto}}</option>
                @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <td>
Tipo Marcaje (type_reg)
        </td>
        <td>
            <select name="type_reg" id="type_reg">
                <option value="1">Entrada</option>
                <option value="2">Salida</option>
            </select>   
        </td>
    </tr>
    <tr>
        <td>
Fecha: (date_reg)
        </td>
        <td>
            <input type="date" name="date_reg" id="date_reg">
        </td>
    </tr>
    <tr>
        <td>
Hora: (hour_reg)
        </td>
        <td>
            <input type="time" name="hour_reg" id="hour_reg">
        </td>
    </tr>
    <tr>
        <td>
Longitud: (long)
        </td>
        <td>
            <input type="text" name="long" value="0">
        </td>
    </tr>
    <tr>
        <td>
          Latitud:(lat)
        </td>
        <td>
            <input type="text" name="lat" value="0">
        </td>
    </tr>
    <tr>
        <td>
Celda:(cellid)
        </td>
        <td>
            <input type="text" name="cellid" value="0">
        </td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" value="Enviar">    </td>
       
    </tr>
</table>


@csrf

       






    </form>
 @endsection