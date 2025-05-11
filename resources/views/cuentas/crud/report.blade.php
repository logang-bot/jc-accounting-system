<!-- Modal Reporte Cuenta -->
<div class="modal fade" id="modalReporteCuenta" tabindex="-1" aria-labelledby="modalReporteCuentaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReporteCuentaLabel">Generar Reporte de Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Aquí iría la lógica para generar un reporte de las cuentas -->
                <form method="GET" action="{{ route('cuentas.reporte') }}">
                    <button type="submit" class="btn btn-info w-100">Generar Reporte</button>
                </form>
            </div>
        </div>
    </div>
</div>
