<div class="modal fade" id="cuentaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar / Editar Cuenta</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="cuentaForm">
                    @csrf
                    <input type="hidden" id="id" name="id">

                    <div class="form-group">
                        <label for="codigo">Código</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" readonly>
                    </div>

                    <div class="form-group">
                        <label for="nombre">Nombre de la Cuenta</label>
                        <input type="text" class="form-control" id="nombre" name="nombre">
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo de Cuenta</label>
                        <select class="form-control" id="tipo" name="tipo">
                            <option value="activo">Activo</option>
                            <option value="pasivo">Pasivo</option>
                            <option value="patrimonio">Patrimonio</option>
                            <option value="ingreso">Ingreso</option>
                            <option value="gasto">Gasto</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("cuentaForm").addEventListener("submit", function (event) {
            event.preventDefault();
            guardarCuenta();
        });
    });

    function guardarCuenta() {
        let formData = new FormData(document.getElementById("cuentaForm"));
        let id = document.getElementById("id").value;
        let url = id ? `/cuentas/${id}` : "{{ route('cuentas.store') }}";
        let method = id ? "PUT" : "POST";

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#cuentaModal').modal('hide');
                location.reload();
            }
        })
        .catch(error => console.error("Error:", error));
    }
</script>
