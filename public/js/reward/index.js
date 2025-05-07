$(document).ready(function () {
    // Mostrar solo el primero al inicio
    $('#como-funciona').hide();

    // Aplica para sidebar y también para tabs móviles
    $('.nav-link-reward, #mobileTabs .nav-link').on('click', function (e) {
        e.preventDefault();

        // Remover clase active de todos
        $('.nav-link-reward, #mobileTabs .nav-link').removeClass('active');

        // Poner la clase active al elemento actual
        $(this).addClass('active');

        // Sincronizar la clase active entre el sidebar y los tabs móviles
        const target = $(this).data('target');

        // Actualizar el contenido visible
        $('.info-reclamacion').fadeOut(200);
        setTimeout(function () {
            $(target).fadeIn(300);
        }, 200);
    });

    // Sincronizar los estados cuando cambies entre móvil y escritorio
    $(window).on('resize', function () {
        if ($(window).width() >= 768) { // Cuando sea vista de escritorio
            const activeTab = $('#mobileTabs .nav-link.active');
            if (activeTab.length) {
                const target = activeTab.data('target');
                // Cambiar la clase active en el sidebar correspondiente
                $('.nav-link-reward').removeClass('active');
                $(`.nav-link-reward[data-target="${target}"]`).addClass('active');
            }
        } else { // Cuando es vista móvil
            const activeTab = $('.nav-link-reward.active');
            if (activeTab.length) {
                const target = activeTab.data('target');
                // Cambiar la clase active en los tabs móviles correspondientes
                $('#mobileTabs .nav-link').removeClass('active');
                $(`#mobileTabs .nav-link[data-target="${target}"]`).addClass('active');
            }
        }
    });

    $('.tab-custom-reward .nav-link').on('click', function (e) {
        e.preventDefault();

        // Activar tab
        $('.tab-custom-reward .nav-link').removeClass('active');
        $(this).addClass('active');

        // Mostrar contenido correspondiente
        $('.milestone-content').removeClass('active');
        $($(this).attr('href')).addClass('active');
    });
});