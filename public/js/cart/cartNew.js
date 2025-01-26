$(document).ready(function () {
    /*const cantidadElemento = $(".cantidad-numero");
    const iconTrash = $("#icon-trash");
    const iconMinus = $("#icon-minus");*/

    /*$(document).on("click", ".icono-cantidad" ,function () {
        let iconTrash = $(this).find("i.fa-trash-alt");
        let iconMinus = $(this).find("i.fa-minus");

        let cantidadElemento = $(this).next();
        let cantidad = parseInt(cantidadElemento.text());

        if ($(this).find("i.fa-trash-alt").is(":visible")) {
            // Acciones para eliminar el producto (solo si la papelera está visible)
            console.log("Eliminar producto");
        } else if ($(this).find("i.fa-minus").is(":visible")) {
            // Restar cantidad (solo si el menos está visible)
            if (cantidad > 1) {
                cantidad -= 1;
                cantidadElemento.text(cantidad);
            }
        } else if ($(this).find("i.fa-plus").length > 0) {
            // Sumar cantidad
            cantidad += 1;
            cantidadElemento.text(cantidad);
        }

        // Mostrar/ocultar íconos según la cantidad
        if (cantidad === 1) {
            iconTrash.show();
            iconMinus.hide();
        } else {
            iconTrash.hide();
            iconMinus.show();
        }
    });*/

    $(document).on("click", '.producto-detalles-link',function (e) {
        console.log("Ingrese");
        e.preventDefault(); // Prevenir el comportamiento predeterminado del enlace
        const popup = $(this).siblings(".detalles-popup");
        popup.toggle(); // Mostrar/ocultar el popup al hacer clic
    });

    // Opcional: Cerrar el popup al hacer clic fuera de él
    $(document).on("click", function (e) {
        if (!$(e.target).closest(".producto-info").length) {
            $(".detalles-popup").hide();
        }
    });

    $(document).on('input', '#observations', function () {
        const currentLength = $(this).val().length; // Usamos $(this) para el textarea actual
        const $charCount = $('#charCount'); // Aseguramos que se seleccione correctamente

        $charCount.text(`${currentLength}/100`);

        if (currentLength >= 90) {
            $charCount.addClass('text-danger');
        } else {
            $charCount.removeClass('text-danger');
        }
    });
});