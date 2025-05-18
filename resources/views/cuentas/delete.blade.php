<!-- Modal Borrar Cuenta -->
<div class="flex items-center justify-center min-h-screen px-4">
    <div class="max-w-sm mx-auto bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">¿Estás seguro?</h2>
        <p class="text-sm text-gray-600 mb-4">Esta acción eliminará la cuenta contable permanentemente.</p>
        <div class="flex justify-end gap-2 mt-4">
            <button type="button" class="hs-overlay-close px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded"
                id="cancelDelete" aria-controls="cuentas-delete" data-hs-overlay="#cuentas-delete">
                Cancelar
            </button>
            <button type="button" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded" id="confirmDelete"
                aria-controls="cuentas-delete" data-hs-overlay="#cuentas-delete">
                Eliminar
            </button>
        </div>
    </div>
</div>

<script>
    let cuentaIdAEliminar = null;

    document.querySelectorAll('[data-cuenta-delete-id]').forEach(button => {
        button.addEventListener('click', function() {
            cuentaIdAEliminar = this.dataset.cuentaDeleteId;
        });
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (!cuentaIdAEliminar) return;

        fetch(`/cuentas/delete/${cuentaIdAEliminar}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`[data-row-id="${cuentaIdAEliminar}"]`)?.remove();
                } else {
                    alert(data.message || 'Error al eliminar la cuenta');
                }

                // Close modal
                const modalEl = document.querySelector('#delete-confirmation-modal');
                window.HSOverlay?.getInstance(modalEl)?.close();
                cuentaIdAEliminar = null;
            })
            .catch(error => {
                alert('Error de red o del servidor.');

                // Close modal
                const modalEl = document.querySelector('#delete-confirmation-modal');
                window.HSOverlay?.getInstance(modalEl)?.close();
                cuentaIdAEliminar = null;
            });
    });
</script>
