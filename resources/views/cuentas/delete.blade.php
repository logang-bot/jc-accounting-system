<!-- resources/views/cuentas/delete.blade.php -->

<!-- Modal Borrar Cuenta -->
<div class="flex items-center justify-center min-h-screen px-4">
    <div class="max-w-sm mx-auto bg-white rounded-lg shadow-lg p-6" id="cuentas-delete">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">¿Estás seguro?</h2>
        <p class="text-sm text-gray-600 mb-4">Esta acción eliminará la cuenta contable permanentemente.</p>

        <div class="flex justify-end gap-2 mt-4">
            <button type="button" class="hs-overlay-close px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded"
                id="cancelDelete" data-hs-overlay="#cuentas-delete">
                Cancelar
            </button>

            <button type="button" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded" id="confirmDelete"
                data-hs-overlay="#cuentas-delete">
                Eliminar
            </button>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let cuentaIdAEliminar = null;

    // Captura los botones que abren el modal y guardamos el id a eliminar
    document.querySelectorAll('[data-cuenta-delete-id]').forEach(button => {
        button.addEventListener('click', function() {
            cuentaIdAEliminar = this.dataset.cuentaDeleteId;
        });
    });

    async function closeOverlayById(overlaySelector) {
        try {
            const modalEl = document.querySelector(overlaySelector);
            if (modalEl) {
                const instance = window.HSOverlay?.getInstance(modalEl);
                instance?.close();
            }
        } catch (e) {
            // no hacer nada si falla el cierre
            // console.warn('No se pudo cerrar overlay', e);
        }
    }

    document.getElementById('confirmDelete').addEventListener('click', async function() {
        if (!cuentaIdAEliminar) return;

        // Mostrar spinner/indicador (opcional)
        Swal.fire({
            title: 'Eliminando...',
            didOpen: () => {
                Swal.showLoading();
            },
            allowOutsideClick: false,
            showConfirmButton: false
        });

        try {
            const token = '{{ csrf_token() }}';
            const res = await fetch(`/cuentas/delete/${cuentaIdAEliminar}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            });

            // Si el servidor responde con 204 No Content -> asumir éxito si el backend borró realmente
            if (res.status === 204) {
                Swal.close();
                Swal.fire({
                    icon: 'success',
                    title: 'Cuenta eliminada',
                    text: 'La cuenta se eliminó correctamente.',
                    timer: 1400,
                    showConfirmButton: false
                });
                document.querySelector(`[data-row-id="${cuentaIdAEliminar}"]`)?.remove();
                await closeOverlayById('#cuentas-delete');
                cuentaIdAEliminar = null;
                return;
            }

            // Intentar leer content-type para decidir cómo parsear
            const contentType = res.headers.get('content-type') || '';

            // Si status no es OK, tratar de obtener mensaje del cuerpo (JSON o texto)
            if (!res.ok) {
                let msg = 'Ocurrió un error en el servidor.';
                if (contentType.includes('application/json')) {
                    const json = await res.json().catch(() => null);
                    if (json && json.message) msg = json.message;
                } else {
                    // Si es HTML o texto, intentar leerlo pero no parsear como JSON
                    const text = await res.text().catch(() => null);
                    if (text) {
                        // intentar extraer mensaje corto de texto (opcional)
                        msg = 'Respuesta inválida del servidor.';
                    }
                }

                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: msg
                });

                await closeOverlayById('#cuentas-delete');
                cuentaIdAEliminar = null;
                return;
            }

            // Si llegó aquí, res.ok === true; parsear JSON SOLO si Content-Type lo indica
            if (contentType.includes('application/json')) {
                const data = await res.json().catch(() => null);

                if (data && data.success) {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Cuenta eliminada',
                        text: data.message || 'La cuenta se eliminó correctamente.',
                        timer: 1400,
                        showConfirmButton: false
                    });

                    // Eliminar fila de la tabla
                    document.querySelector(`[data-row-id="${cuentaIdAEliminar}"]`)?.remove();

                } else {
                    // backend devolvió JSON pero success = false o no hay JSON
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: (data && data.message) ? data.message :
                            'No se pudo eliminar la cuenta.'
                    });
                }

            } else {
                Swal.close();
                Swal.fire({
                    icon: 'warning',
                    title: 'Estado incierto',
                    text: 'El servidor respondió con un tipo de contenido inesperado. Comprueba que la ruta devuelva JSON. Si la cuenta fue borrada, recarga la página para sincronizar.',
                    confirmButtonText: 'Recargar',
                    showCancelButton: true,
                    cancelButtonText: 'Cerrar'
                }).then(result => {
                    if (result.isConfirmed) location.reload();
                });
            }

            await closeOverlayById('#cuentas-delete');
            cuentaIdAEliminar = null;

        } catch (err) {
            // Aquí caía tu .catch() anterior: ocurre cuando response.json() lanza o hay error de red
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error del servidor',
                text: 'Ocurrió un problema de red o el servidor devolvió una respuesta no válida.'
            });

            await closeOverlayById('#cuentas-delete');
            cuentaIdAEliminar = null;
        }
    });
</script>
