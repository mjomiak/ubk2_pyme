@extends('layouts.app')

@section('content')


<div class="row">
    <div class="col-md-12">   <div style="background-color: #e9ecef;">
        {!! MigasFacade::render('sys/uyp') !!}
    </div>   </div>
</div>


<div class="row">
    <div class="col-md-2">
        <br>
    </div>

    <div class="col-md-8">

        <form action="{{route('SigmaReadPermisos')}}" method="post" id="FormRol">
            @csrf
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <select name="rol" id="rol" class="form-control">
                        <option value="">SELECCIONE UN ROL</option>
                        @foreach($roles as $r)
                        <option value="{{$r->cod_rol}}">{{$r->cod_rol}} - {{$r->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select id="categoria" name="categoria" class="form-control" disabled onchange="enviarFormulario()">
                  
                    </select>
                </div>

            </div>

        </form>


    </div>
</div>

<div class="row">
    <div class="col-md-2"><br></div>
</div>


<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        @isset($mensaje_filtro)
    <h5>{{$mensaje_filtro}}</h5>
@endisset
        @isset($permisos)

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Rol</th>
                    <th>Nombre</th>
                    <th>Permitido</th>
                </tr>
            </thead>

            <tbody>
                @foreach($permisos as $p)
                <tr>
                    <td>{{ $loop->iteration }} </td>
                    <td>{{$p->cod_rol}}</td>
                    <td>{{$p->nombre}}</td>
                    <td>
                        <input type="checkbox" id="permiso" onchange="cambiarEstado({{$p->id}}, this.checked)"
                            @if($p->permitido==1) checked @endif>





                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>


        @endif
    </div>
</div>
<script>

    function enviarFormulario() {
        // Obtener el formulario por su ID
        var formulario = document.getElementById('FormRol');

        // Enviar el formulario
        formulario.submit();
    }

    function mostrarModal(titulo, mensaje, tipo) {
        // Elimina el modal anterior si existe
        $('#miModal').remove();

        // Crea el modal
        var modal = `
        <div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${titulo}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>${mensaje}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-${tipo}" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    `;

        // Agrega el modal al cuerpo del documento
        $('body').append(modal);

        // Muestra el modal
        $('#miModal').modal('show');
    }

    function cambiarEstado(id, estado) {

        var datos = { id: id, estado: estado };
        var csrfToken = "{{ csrf_token() }}";
        url = "{{ route('SigmaSetPermiso') }}";

        var xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("X-CSRF-Token", csrfToken);
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Éxito: La solicitud fue exitosa
                console.log('Éxito:', xhr.responseText);
                mostrarModal('Éxito', 'Datos enviados correctamente.', 'success');
            } else {
                // Fracaso: La solicitud no fue exitosa
                console.error('Error:', xhr.statusText);
                mostrarModal('Error', 'No se pudieron enviar los datos.', 'danger');
            }
        };


        // Manejar errores de red
        xhr.onerror = function () {
            console.error('Error de red.');
            mostrarModal('Error', 'Error de red al intentar enviar los datos.', 'danger');
        };

        // Convertir los datos a formato JSON y enviar la solicitud
        xhr.send(JSON.stringify(datos));


    }//function
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rolSelect = document.getElementById('rol');
        const categoriaSelect = document.getElementById('categoria');



        // Actualizar categorías al cambiar el rol seleccionado
        rolSelect.addEventListener('change', () => {
            const rolId = rolSelect.value;
            categoriaSelect.disabled = true;
            categoriaSelect.innerHTML = '<option value="">Selecciona una categoría</option>';

            if (rolId !== '') {
                fetch(`/get-categorias/${rolId}`)
                    .then(response => response.json())
                    .then(data => {
                        const option = document.createElement('option');
                            option.value = "all";
                            option.textContent = "Todas";
                            categoriaSelect.appendChild(option);
                        data.forEach(categoria => {

                            const option = document.createElement('option');
                            option.value = categoria.categoria;
                            option.textContent = categoria.categoria;
                            categoriaSelect.appendChild(option);
                        });

                        categoriaSelect.disabled = false;
                    });
            }
        });

        categoriaSelect.addEventListener()
    });
</script>


@endsection