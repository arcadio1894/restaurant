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
        let title = "Tipos de usuarios de hoy"; // Default para 'daily'

        if (filter === 'weekly') {
            title = "Tipos de usuario de la última semana";
        } else if (filter === 'monthly') {
            title = "Tipos de usuario de los últimos 7 meses";
        } else if (filter === 'date_range') {
            let start = startDate ? new Date(startDate + "T00:00:00").toLocaleDateString() : '';
            let end = endDate ? new Date(endDate + "T00:00:00").toLocaleDateString() : '';
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

    fetchChartDataPromo(selectedFilter);

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

    $(".filter-btn-promo").click(function () {
        selectedFilter = $(this).data("filter");

        if (selectedFilter === "date_range_promo") {
            let startDate = $("#start_date_promo").val();
            let endDate = $("#end_date_promo").val();

            if (!startDate || !endDate) {
                alert("Por favor, seleccione ambas fechas.");
                return;
            }

            fetchChartDataPromo(selectedFilter, startDate, endDate);
        } else {
            fetchChartDataPromo(selectedFilter);
        }
    });

    function fetchChartDataPromo(filter, startDate = null, endDate = null) {

        updateChartTitlePromo(filter, startDate, endDate);

        $.ajax({
            url: '/dashboard/promos/chart-data',
            type: 'GET',
            data: { filter, start_date: startDate, end_date: endDate },
            success: function (response) {
                updateChartPromo(response);
            },
            error: function () {
                alert('Error al obtener los datos del gráfico.');
            }
        });
    }

    function updateChartPromo(data) {
        let tbody = document.querySelector("#body-promos");
        tbody.innerHTML = ""; // Limpiar la tabla antes de agregar nuevos datos

        data.coupons.forEach((coupon, index) => {
            let clone = activateTemplate("#template-promo");

            clone.querySelector("[data-i]").innerHTML = (index + 1) + ".";
            clone.querySelector("[data-code]").innerHTML = coupon.code;
            clone.querySelector("[data-progress]").style.width = coupon.percentage + "%";
            clone.querySelector("[data-progress]").classList.add(getProgressBarClass(coupon.percentage));
            clone.querySelector("[data-percentage]").innerHTML = coupon.count;
            clone.querySelector("[data-percentage]").classList.add(getProgressBarClass(coupon.percentage));

            tbody.appendChild(clone);
        });

        $("#title-promo").html(window.chartTitlePromo);
    }

    function updateChartTitlePromo(filter, startDate, endDate) {
        let title = "Promociones usadas el día de hoy"; // Default for 'daily'

        if (filter === 'weekly') {
            title = "Promociones usadas en la última semana";
        } else if (filter === 'monthly') {
            title = "Promociones usadas en los últimos 7 meses";
        } else if (filter === 'date_range_promo') {
            let start = startDate ? new Date(startDate + "T00:00:00").toLocaleDateString() : '';
            let end = endDate ? new Date(endDate + "T00:00:00").toLocaleDateString() : '';
            title = `Promociones usadas desde ${start} hasta ${end}`;
        }

        // Almacenar el título dinámico en una variable global para usarlo en Chart.js
        window.chartTitlePromo = title;
    }

    // Función para asignar colores a la barra de progreso según el porcentaje
    function getProgressBarClass(percentage) {
        if (percentage >= 75) {
            return "bg-success";
        } else if (percentage >= 50) {
            return "bg-primary";
        } else if (percentage >= 25) {
            return "bg-warning";
        } else {
            return "bg-danger";
        }
    }

    // Graficos de ventas
    let saleChart;
    let selectedFilterSale = 'daily';

    function fetchChartDataSale(filter, startDate = null, endDate = null) {
        // Actualizar el título según el filtro seleccionado
        updateChartTitleSale(filter, startDate, endDate);

        $.ajax({
            url: '/dashboard/orders/chart-data-sale',
            type: 'GET',
            data: { filter, start_date: startDate, end_date: endDate },
            success: function (response) {
                updateChartSale(response);
                updateKnobsSale(response)
            },
            error: function () {
                alert('Error al obtener los datos del gráfico.');
            }
        });
    }

    function updateChartTitleSale(filter, startDate, endDate) {
        let title = "Total de ventas de hoy"; // Default for 'daily'

        if (filter === 'weekly') {
            title = "Total de ventas de la última semana";
        } else if (filter === 'monthly') {
            title = "Total de ventas de los últimos 7 meses";
        } else if (filter === 'date_range') {
            let start = startDate ? new Date(startDate + "T00:00:00").toLocaleDateString() : '';
            let end = endDate ? new Date(endDate + "T00:00:00").toLocaleDateString() : '';
            title = `Total de ventas desde ${start} hasta ${end}`;
        }

        // Almacenar el título dinámico en una variable global para usarlo en Chart.js
        window.chartTitleSale = title;
    }

    function updateChartSale(data) {
        let ctx = $("#sale-chart").get(0).getContext("2d");

        // Destruir el gráfico anterior si existe
        if (saleChart) {
            saleChart.destroy();
        }

        saleChart = new Chart(ctx, {
            type: 'line',  // Gráfico de línea para visualizar tendencia
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: "Total de ventas (S/.)", // Etiqueta del dataset
                        fill: false,
                        borderColor: "#ffffff", // Color amarillo para resaltar
                        borderWidth: 2,
                        data: data.sales,
                        lineTension: 0.1 // Pequeña curvatura en la línea
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: true,
                    text: window.chartTitleSale, // Usar el título dinámico
                    fontSize: 14,
                    fontStyle: 'bold',
                    padding: 3,
                    align: 'center',
                    fontColor: "#ffffff", // Título en blanco
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            fontColor: "#ffffff", // Números del eje X en blanco
                            autoSkip: false,
                            padding: 10
                        },
                        gridLines: {
                            color: "#ffffff", // Líneas del grid en blanco
                            zeroLineColor: "#ffffff"
                        },
                        offset: true
                    }],
                    yAxes: [{
                        ticks: {
                            fontColor: "#ffffff", // Números del eje Y en blanco
                            beginAtZero: true,
                            callback: function(value) {
                                return "S/ " + value.toLocaleString(); // Formato en soles
                            }
                        },
                        gridLines: {
                            color: "#ffffff", // Líneas del grid en blanco
                            zeroLineColor: "#ffffff"
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontColor: "#ffffff" // Color blanco para la leyenda
                    }
                }
            }
        });
    }

    // Cargar datos iniciales (Diario por defecto)
    fetchChartDataSale(selectedFilterSale);

    // Manejo de botones de filtro
    $(".filter-btn-sale").click(function () {
        selectedFilter = $(this).data("filter");

        if (selectedFilter === "date_range") {
            let startDate = $("#start_date_sale").val();
            let endDate = $("#end_date_sale").val();

            if (!startDate || !endDate) {
                alert("Por favor, seleccione ambas fechas.");
                return;
            }

            fetchChartDataSale(selectedFilter, startDate, endDate);
        } else {
            fetchChartDataSale(selectedFilter);
        }
    });

    function updateKnobsSale(data) {
        $('#knobWhatsappSale').val(data.whatsapp_percentage).trigger('change');
        $('#knobWebSale').val(data.web_percentage).trigger('change');
        $('#knobTotalSale').val(data.total_percentage).trigger('change');

        $('#quantityKnobWhatsappSale').text('S/. '+data.total_whatsapp);
        $('#quantityKnobWebSale').text('S/. '+data.total_web);
        $('#quantityKnobTotalSale').text('S/. '+data.total);
    }


    // Graficos de Ingresos VS Egresos
    let utilidadChart;
    let selectedFilterUtilidad = 'daily';

    function fetchChartDataUtilidad(filter, startDate = null, endDate = null) {
        // Actualizar el título según el filtro seleccionado
        updateChartTitleUtilidad(filter, startDate, endDate);

        $.ajax({
            url: '/dashboard/orders/chart-data-utilidad',
            type: 'GET',
            data: { filter, start_date: startDate, end_date: endDate },
            success: function (response) {
                updateChartUtilidad(response);
                updateKnobsUtilidad(response)
            },
            error: function () {
                alert('Error al obtener los datos del gráfico.');
            }
        });
    }

    function updateChartTitleUtilidad(filter, startDate, endDate) {
        let title = "Ingresos Vs Egresos de hoy"; // Default for 'daily'

        if (filter === 'weekly') {
            title = "Ingresos Vs Egresos de la última semana";
        } else if (filter === 'monthly') {
            title = "Ingresos Vs Egresos de los últimos 7 meses";
        } else if (filter === 'date_range') {
            let start = startDate ? new Date(startDate + "T00:00:00").toLocaleDateString() : '';
            let end = endDate ? new Date(endDate + "T00:00:00").toLocaleDateString() : '';
            title = `Ingresos Vs Egresos desde ${start} hasta ${end}`;
        }

        // Almacenar el título dinámico en una variable global para usarlo en Chart.js
        window.chartTitleUtilidad = title;
    }

    function updateChartUtilidad(data) {
        let ctx = $("#utilidad-chart").get(0).getContext("2d");

        // Destruir el gráfico anterior si existe
        if (utilidadChart) {
            utilidadChart.destroy();
        }

        utilidadChart = new Chart(ctx, {
            type: 'line',  // Gráfico de línea
            data: {
                labels: data.labels,  // Fechas en el eje X
                datasets: [
                    {
                        label: "Ingresos (S/.)",
                        fill: false,
                        borderColor: "#28a745", // Verde para ingresos
                        backgroundColor: "rgba(40, 167, 69, 0.2)", // Sombra verde
                        borderWidth: 2,
                        data: data.incomes, // Datos de ingresos
                        lineTension: 0.1
                    },
                    {
                        label: "Egresos (S/.)",
                        fill: false,
                        borderColor: "#dc3545", // Rojo para egresos
                        backgroundColor: "rgba(220, 53, 69, 0.2)", // Sombra roja
                        borderWidth: 2,
                        data: data.expenses, // Datos de egresos
                        lineTension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: true,
                    text: window.chartTitleUtilidad, // Usar el título dinámico
                    fontSize: 14,
                    fontStyle: 'bold',
                    padding: 3,
                    align: 'center',
                    fontColor: "#ffffff", // Título en blanco
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            fontColor: "#ffffff",
                            autoSkip: false,
                            padding: 10
                        },
                        gridLines: {
                            color: "rgba(255,255,255,0.2)",
                            zeroLineColor: "#ffffff"
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            fontColor: "#ffffff",
                            beginAtZero: true,
                            callback: function(value) {
                                return "S/ " + value.toLocaleString(); // Formato en soles
                            }
                        },
                        gridLines: {
                            color: "rgba(255,255,255,0.2)",
                            zeroLineColor: "#ffffff"
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontColor: "#ffffff"
                    }
                }
            }
        });
    }

    // Cargar datos iniciales (Diario por defecto)
    fetchChartDataUtilidad(selectedFilterUtilidad);

    // Manejo de botones de filtro
    $(".filter-btn-utilidad").click(function () {
        selectedFilter = $(this).data("filter");

        if (selectedFilter === "date_range") {
            let startDate = $("#start_date_utilidad").val();
            let endDate = $("#end_date_utilidad").val();

            if (!startDate || !endDate) {
                alert("Por favor, seleccione ambas fechas.");
                return;
            }

            fetchChartDataUtilidad(selectedFilter, startDate, endDate);
        } else {
            fetchChartDataUtilidad(selectedFilter);
        }
    });

    function updateKnobsUtilidad(data) {
        $('#quantityKnobIngresos').text('S/. '+data.total_income);
        $('#quantityKnobEgresos').text('S/. '+data.total_expense);
        $('#quantityKnobUtilidad').text('S/. '+data.profit);
    }

});

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}