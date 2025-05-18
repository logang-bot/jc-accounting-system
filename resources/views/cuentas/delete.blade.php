<!-- Modal Borrar Cuenta -->
<div class="modal fade" id="modalBorrarCuenta" tabindex="-1" aria-labelledby="modalBorrarCuentaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBorrarCuentaLabel">Borrar Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar esta cuenta?</p>
                <form id="formBorrarCuenta" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-danger me-2">Sí, Borrar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    document.getElementById("btnBorrar").addEventListener("click", function() {
        if (cuentaSeleccionadaId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará la cuenta seleccionada.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, borrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar el formulario manualmente
                    borrarCuenta(cuentaSeleccionadaId);
                }
            });
        } else {
            Swal.fire('Atención', 'Debes seleccionar una cuenta primero.', 'info');
        }
    });

    function borrarCuenta(id) {
        fetch(`/cuentas/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        '¡Eliminado!',
                        data.message,
                        'success'
                    ).then(() => {
                        // Recargar página o recargar la tabla
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', 'No se pudo eliminar la cuenta.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Hubo un problema al intentar eliminar la cuenta.', 'error');
            });
    }
</script>
