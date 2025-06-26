jQuery(document).ready(function($) {

     //Evento al hacer clic en los botones de navegación ("Anteriores" y "Siguientes")
    $(document).on('click', '.ct-boton', function(e) {
        e.preventDefault();

        //Si el botón está deshabilitado, no hacer nada
        if ($(this).prop('disabled')) return;

        const container = $('#ct-eventos'); //Contenedor principal de eventos
        const mes = $(this).data('mes'); //Nuevo mes solicitado
        const anio = $(this).data('anio'); //Nuevo año solicitado

        //Agrega clase para mostrar animación o efecto de carga
        container.addClass('ct-cargando');

        //Realiza petición AJAX para cargar eventos del nuevo trimestre
        $.ajax({
            url: ct_ajax.ajaxurl, //URL de admin-ajax.php proporcionada por wp_localize_script
            method: 'POST',
            data: {
                action: 'ct_cargar_torneos', //Nombre de la acción AJAX en PHP
                mes: mes,
                anio: anio
            },
            success: function(response) {
                //Reemplaza el contenido con el nuevo HTML recibido
                container.html(response);
                container.removeClass('ct-cargando');
            }
        });
    });
});
