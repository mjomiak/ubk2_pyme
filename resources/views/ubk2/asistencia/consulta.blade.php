<form action="{{route('MuestraAsistencia')}}" method="post">
    @csrf

    <label for="trabajador">Trabajador</label>
    <select name="trabajador" id="trabajador">
        @foreach($trabas as $t)
        <option value="{{$t->id}}">{{$t->nombreCompleto}}</option>
        @endforeach
    </select>


    <label for="desde">Desde</label>
    <input type="date" name="desde" id="desde">
    <label for="hasta">Hasta</label>
    <input type="date" name="hasta" id="hasta">
    <input type="submit" value="Enviar">

</form>