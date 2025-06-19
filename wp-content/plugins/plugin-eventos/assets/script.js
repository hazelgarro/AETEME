jQuery(document).ready(function($) {
    $(document).on('click', '.ct-boton', function(e) {
        e.preventDefault();

        if ($(this).prop('disabled')) return;

        const container = $('#ct-eventos');
        const mes = $(this).data('mes');
        const anio = $(this).data('anio');

        container.addClass('ct-cargando');

        $.ajax({
            url: ct_ajax.ajaxurl,
            method: 'POST',
            data: {
                action: 'ct_cargar_torneos',
                mes: mes,
                anio: anio
            },
            success: function(response) {
                container.html(response);
                container.removeClass('ct-cargando');
            }
        });
    });
});
