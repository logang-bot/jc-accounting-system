"use strict";

// document.addEventListener("DOMContentLoaded", () => {
document.querySelectorAll(".delete-btn").forEach((button) => {
    button.addEventListener("click", () => {
        const id = button.getAttribute("data-empresa-id");
        confirmDelete(id);
    });
});
// });

function confirmDelete(id) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción no se puede deshacer",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}
