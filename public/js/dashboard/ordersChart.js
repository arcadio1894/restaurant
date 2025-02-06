$(document).ready(function () {

    $('#dateRangeModal').on('show.bs.modal', function () {
        // Establecer z-index solo cuando el modal se activa
        $(this).css('z-index', 9999); // Fuerza el z-index del modal a ser el más alto
        $('.modal-backdrop').css('z-index', 9998);  // El fondo del modal debe estar debajo del modal
    });

    $('#dateRangeModal').on('hidden.bs.modal', function () {
        // Restablecer el z-index después de cerrar el modal
        $(this).css('z-index', '');  // Restablece el z-index del modal
        $('.modal-backdrop').css('z-index', ''); // Restablece el z-index del fondo del modal
    });

    // Habilitar el drag and drop para las tarjetas
    $('.card').each(function () {
        $(this).find('.card-header').css('cursor', 'move');  // Cambiar el cursor al mover
    });

    // Habilitar drag and drop para los cards
    $('.card').draggable({
        handle: '.card-header', // Se puede mover arrastrando desde el header
        stack: '.card',          // Mantener las tarjetas en una pila
        revert: 'invalid'        // Revertir la tarjeta si no es colocada en un contenedor válido
    });

    $(".knob").knob();

    let lineChart;
    let selectedFilter = 'daily';

    function fetchChartData(filter, startDate = null, endDate = null) {
        // Actualizar el título según el filtro seleccionado
        updateChartTitle(filter, startDate, endDate);

        $.ajax({
            url: '/dashboard/orders/chart-data',
            type: 'GET',
            data: { filter, start_date: startDate, end_date: endDate },
            success: function (response) {
                updateChart(response);
                updateKnobs(response); // Actualiza los knobs
            },
            error: function () {
                alert('Error al obtener los datos del gráfico.');
            }
        });
    }

    function updateChartTitle(filter, startDate, endDate) {
        let title = "Tipos de usuarios de hoy"; // Default for 'daily'

        if (filter === 'weekly') {
            title = "Tipos de usuario de la última semana";
        } else if (filter === 'monthly') {
            title = "Tipos de usuario de los últimos 7 meses";
        } else if (filter === 'date_range') {
            let start = startDate ? new Date(startDate).toLocaleDateString() : '';
            let end = endDate ? new Date(endDate).toLocaleDateString() : '';
            title = `Tipos de usuario desde ${start} hasta ${end}`;
        }

        // Almacenar el título dinámico en una variable global para usarlo en Chart.js
        window.chartTitle = title;
    }

    function updateChart(data) {
        let ctx = $("#lineChart").get(0).getContext("2d");

        // Destruir el gráfico anterior si existe
        if (lineChart) {
            lineChart.destroy();
        }

        lineChart = new Chart(ctx, {
            type: 'line',  // Cambiar de 'bar' a 'line' para el gráfico lineal
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: "Usuarios WhatsApp",
                        fill: false,  // No llenar el área bajo la línea
                        borderColor: "#dc3545",
                        borderWidth: 2,
                        data: data.whatsapp,
                        lineTension: 0.1  // Curvatura de la línea
                    },
                    {
                        label: "Usuarios Web",
                        fill: false,
                        borderColor: "#007bff",
                        borderWidth: 2,
                        data: data.web,
                        lineTension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: true,
                    text: window.chartTitle,  // Usar el título dinámico
                    fontSize: 14,
                    fontStyle: 'bold',
                    padding: 3,
                    align: 'center'  // Asegura que el título esté centrado
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            autoSkip: false,
                            // Ajusta la posición de los puntos con offset
                            padding: 10
                        },
                        offset: true  // Esto agrega un pequeño desplazamiento en el eje X
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }

    // Función para actualizar los knobs
    function updateKnobs(data) {
        // Actualizar los valores de los knobs con los totales
        $('#knobWhatsapp').val(data.whatsapp_percentage).trigger('change');
        $('#knobWeb').val(data.web_percentage).trigger('change');
        $('#knobTotal').val(data.total_percentage).trigger('change');

        // Actualizar los valores de los totales en las etiquetas
        $('#quantityKnobWhatsapp').text(data.total_whatsapp); // Total de WhatsApp
        $('#quantityKnobWeb').text(data.total_web); // Total de Web
        $('#quantityKnobTotal').text(data.total); // Total general
    }

    // Cargar datos iniciales (Diario por defecto)
    fetchChartData(selectedFilter);

    // Manejo de botones de filtro
    $(".filter-btn").click(function () {
        selectedFilter = $(this).data("filter");

        if (selectedFilter === "date_range") {
            let startDate = $("#start_date").val();
            let endDate = $("#end_date").val();

            if (!startDate || !endDate) {
                alert("Por favor, seleccione ambas fechas.");
                return;
            }

            fetchChartData(selectedFilter, startDate, endDate);
        } else {
            fetchChartData(selectedFilter);
        }
    });
});
