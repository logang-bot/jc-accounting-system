"use strict";

document.addEventListener("DOMContentLoaded", () => {
    /* ---------------- ELIMINAR EMPRESA ---------------- */
    document.querySelectorAll(".delete-btn").forEach((button) => {
        button.addEventListener("click", () => {
            const id = button.dataset.empresaId;
            confirmDelete(id);
        });
    });

    /* ---------------- ARCHIVAR/ACTIVAR EMPRESA ---------------- */
    document.querySelectorAll(".archive-btn").forEach((button) => {
        button.addEventListener("click", () => {
            const id = button.dataset.empresaId;
            const isActive = button.textContent.trim() === "Archivar"; 
            confirmArchive(id, isActive);
        });
    });

    /* ---------------- CASA MATRIZ / SUCURSAL ---------------- */
    const casaMatrizCheckbox = document.getElementById("casa_matriz");
    const sucursalContainer = document.getElementById("sucursalContainer");

    if (casaMatrizCheckbox && sucursalContainer) {
        // marcar casa matriz al cargar
        casaMatrizCheckbox.checked = true;
        // ocultar sucursal si es casa matriz
        sucursalContainer.style.display = "none";

        casaMatrizCheckbox.addEventListener("change", () => {
            sucursalContainer.style.display = casaMatrizCheckbox.checked ? "none" : "block";
        });
    }

    /* ---------------- SESIÓN ÉXITO ---------------- */
    if (window.sessionSuccessMessage) {
        Swal.fire({
            title: "¡Éxito!",
            text: window.sessionSuccessMessage,
            icon: "success",
            confirmButtonText: "OK"
        });
    }
});

/* ---------------- FUNCIONES ---------------- */
function confirmDelete(id) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) document.getElementById(`delete-form-${id}`).submit();
    });
}

function confirmArchive(id, isActive) {
    Swal.fire({
        title: isActive ? "¿Archivar empresa?" : "¿Activar empresa?",
        text: isActive
            ? "La empresa será archivada y quedará inactiva."
            : "La empresa será activada y estará disponible.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: isActive ? "Sí, archivar" : "Sí, activar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) document.getElementById(`archive-form-${id}`).submit();
    });
}
 