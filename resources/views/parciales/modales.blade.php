





<!-- Modal Resultado Exitoso------------------------------------------------------------------------------------------------------------------>
<div class="modal fade" id="modal_success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resultado</h5>
                <button type="button" class="close" onclick="cerrarDialogo('modal_success')" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(isset($success))
                <div id="msg_exito" class="alert alert-success">
                    {!! $success !!}
                </div>
          
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    onclick="cerrarDialogo('modal_success')">Entendido</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Resultado Error------------------------------------------------------------------------------------------------------------------------>
<div class="modal fade" id="modal_error" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resultado</h5>
                <button type="button" class="close" onclick="cerrarDialogo('modal_error')" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(isset($error))
                <div id="msg_exito" class="alert alert-danger">
                    {!! $error !!}
                </div>
            
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="cerrarDialogo('modal_error')">Entendido</button>
            </div>
        </div>
    </div>
</div>



<!-- Contenido del modal -->
<div class="modal fade" id="dlg_verificacion" tabindex="-1" role="dialog" aria-labelledby="dlg_verificacion"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="miFormulario" action="" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="midlg_verificacion">Confirmación</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Contenido de la ventana modal -->
                    <div class="alert alert-info">
                        <p>La acción solicitada requiere confirmación, para proceder ingrese sus credenciales y presione
                            Ejecutar para Cancelar, presione Cancelar o cierre la ventana </p>
                    </div>

                    @csrf
                    <input type="hidden" id="hidden_id" name="hidden_id" value="-1">
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="pass1" name="pass1" autocomplete="new-password"
                            placeholder="Ingrese Contraseña">
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="togglePasswordVisibility('pass1')">
                            Mostrar/ocultar
                        </button>
                    </div>


                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="pass2" name="pass2" autocomplete="new-password"
                            placeholder="Repita Contraseña">
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="togglePasswordVisibility('pass2')">
                            Mostrar/ocultar
                        </button>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Ejecutar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- -->

<div class="modal fade" id="noPermiso" tabindex="-1" role="dialog" aria-labelledby="miVentanaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Información</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Contenido de la ventana modal -->
                <div class="alert alert-info">
                    <p>No tiene permiso para realizar la acción solicitada </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
                </div>

            </div>
        </div>
    </div>
</div>





<script>
    function DialogoResultado(dlg) {
        // Obtiene el modal por su ID
        const modal = document.getElementById(dlg);

        // Muestra el modal
        modal.classList.add('show');
        modal.style.display = 'block';

        // Desvanece el modal después de 5 segundos
        setTimeout(function () {
            // Oculta el modal
            modal.classList.remove('show');
            modal.style.display = 'none';
        }, 5000);
    }
    function cerrarDialogo(dlg) {
        // Cierra el modal
        const modal = document.getElementById(dlg);
        modal.classList.remove('show');
        modal.style.display = 'none';

        // Limpia el temporizador si aún no ha expirado
        clearTimeout(timeoutId);
    }
</script>


<script>
    function abrirModal(user, ruta) {

        $('#miFormulario').attr('action', ruta);
        $('#hidden_id').attr('value', user);
        $('#dlg_verificacion').modal('show');
    }
    function noPermiso() {


        $('#noPermiso').modal('show');
    }

</script>

<script>
    function togglePasswordVisibility(id) {
        var passwordInput = document.getElementById(id);
        passwordInput.type = (passwordInput.type === 'password') ? 'text' : 'password';
    }
</script>




@if(isset($success))
<script>
    DialogoResultado('modal_success');
</script>
@endif

@if(isset($error))

<script>
    DialogoResultado('modal_error');
</script>

@endif


