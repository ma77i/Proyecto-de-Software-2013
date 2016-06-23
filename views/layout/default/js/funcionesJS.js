$(document).ready(function() {
    $("#estMaps").hide();
    $("#contenedor3").hide();
    var map;
    function initialize() {
        $("#estMaps").show();
        google.maps.visualRefresh = true;



        var mapOptions = {
            zoom: 10,
            center: new google.maps.LatLng(2, 3),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };


        map = new google.maps.Map(document.getElementById("estMaps"),
                mapOptions);





    }

    function placeMarker(position, map) {
       
        var marker = new google.maps.Marker({
            position: position,
            map: map
           

        });


        map.panTo(position);


    }




    var generarFormAyudantes = function(numero) {
        var formulario = '<div id="ayudante' + numero + '" >';
        formulario = formulario + '<p class="tituloRegistro">Registro de Ayudantes </p>';
        formulario = formulario + '<p ayudante numero ' + numero + '>';
        formulario = formulario + ' <p><label>Nombre</label>';
        formulario = formulario + '<input id="nombreAyu" name="nomAyu' + numero + '" type="text" maxlength="40" placeholder="Ingrese nombre" required>';
        formulario = formulario + '<p><label>Apellido</label>';
        formulario = formulario + '<input id="apellidoAyu" name="apeAyu' + numero + '"type="text" maxlength="40" placeholder="Ingrese apellido" required>';
        formulario = formulario + '<p><label>DNI</label>'
        formulario = formulario + '<input id="dniAyu" name="dniAyu' + numero + '"type="text" maxlength="40" placeholder="Ingrese dni"required>';
        formulario = formulario + '<p><label>Usuario</label>'
        formulario = formulario + '<input id="usuariAyu" name="usuarioAyu' + numero + '"type="text" maxlength="40" placeholder="Ingrese un nombre de usuario" required>';
        formulario = formulario + '<p><label>Clave</label>'
        formulario = formulario + '<input id="claveAyu" name="claveAyu' + numero + '"type="text" maxlength="40" placeholder="Ingrese una clave" required>';

        formulario = formulario + '</div>';
        return formulario;
    };

    $("#comboayudantes").change(function() {

        if ($(this).val() == 1) {
            $('#cargaA').empty();
            $('#cargaA').append(generarFormAyudantes(1));
        }
        if ($(this).val() == 2) {
            $('#cargaA').empty();
            $('#cargaA').append(generarFormAyudantes(1));
            $('#cargaA').append(generarFormAyudantes(2));
        }
        if ($(this).val() == 0) {
            $('#cargaA').empty();
        }

    });


// var generarFormIngreso = function(numero) {
//        var formulario = '<p><div id="ingreso' + numero + '" >';
//        formulario = formulario + '<p class="tituloRegistro">--------------------------------------------------------------------</p>';
//        
//        formulario = formulario + "<p><label>Seleccione especie: </label>";
//        formulario = formulario + "<SELECT id='comboespecies' class='combosIngreso'   name='comboespecies' SIZE=1 >"; 
//                                + "<option value='ninguna'>Seleccione una especie</option>";
//                                + "'{%for especie in parametros.listadoespecies%}'";  
//                                    "<option value='{{especie.idespecie}}'>{{especie.nombre}}</option>";
//                                + "'{%endfor%}'";
//                                + "</SELECT> </p>";
//        formulario = formulario + "<p><label>Seleccione ejemplar:</label>";
//        formulario = formulario + "<SELECT id='comboplantas' class='combosIngreso' onchange='enableInputColor(this.value)' class='comboBoxes'  name='comboplantas' SIZE=1 ></SELECT> </p>";
//        formulario = formulario + "<p> <label>Seleccione color:</label>";
//        formulario = formulario + "<SELECT id='combocolores' class='combosIngreso'  class='comboBoxes' name='combocolores' SIZE=1 >"; 
//                                + "{%for color in parametros.listadocolores%}";
//                                + "<option value='{{color.idcolor}}'>'{{color.color}}'</option>";
//                                + "{%endfor%}";     
//                                + "</SELECT> </p>";
//        formulario = formulario + "<p><label>Ingrese cantidad:</label>";
//        formulario = formulario + "<input type='text' class='combosIngreso' required='required' placeholder='Ingrese una cantidad' name='cantidadingresada'  id='cantidadingresada' value=''  /> </p>";
//
//        formulario = formulario + "</div></p>";
//        return formulario;
//    };
//
//    $("#botoncargaI").click(function() {
//            var cantI=$('#hiddeningreso').val();
//            $('#hiddeningreso').val(cantI+1);    
//            $('#cargaI').append(generarFormIngreso(1));
//        
//        }
//
//    );


    $("#combocanting").change(function() {
        var op = $("#combocanting").val();
        if (op == 1) {
            $("#divingreso1").show();
            $("#divingreso2").hide();
            $("#divingreso3").hide();

        }
        if (op == 2) {
            $("#divingreso1").show();
            $("#divingreso2").show();
            $("#divingreso3").hide();

        }

        if (op == 3) {
            $("#divingreso1").show();
            $("#divingreso2").show();
            $("#divingreso3").show();

        }

    });


    $("#comboespecies").change(function() {
        $("#lcomboplantas").show();
        $("#comboplantas").show();
        $.ajax({
            type: "POST",
            url: "ingreso/listarplantasdeespecie",
            async: false,
            data: 'especie=' + $("#comboespecies").val(),
            dataType: "json",
            success: function(datos) {
                $("#comboplantas").empty();
                for (var i = 0; i < datos.length; i++) {
                    $("#comboplantas").append('<option value="' + datos[i].idplanta + '">' + datos[i].nombre + '</option>');
                }
            }
        });
    });

    $("#comboespecies2").change(function() {
        $("#lcomboplantas2").show();
        $("#comboplantas2").show();
        $.ajax({
            type: "POST",
            url: "ingreso/listarplantasdeespecie",
            async: false,
            data: 'especie=' + $("#comboespecies2").val(),
            dataType: "json",
            success: function(datos) {
                $("#comboplantas2").empty();
                for (var i = 0; i < datos.length; i++) {
                    $("#comboplantas2").append('<option value="' + datos[i].idplanta + '">' + datos[i].nombre + '</option>');
                }
            }
        });
    });

    $("#comboespecies3").change(function() {
        $("#lcomboplantas3").show();
        $("#comboplantas3").show();
        $.ajax({
            type: "POST",
            url: "ingreso/listarplantasdeespecie",
            async: false,
            data: 'especie=' + $("#comboespecies3").val(),
            dataType: "json",
            success: function(datos) {
                $("#comboplantas3").empty();
                for (var i = 0; i < datos.length; i++) {
                    $("#comboplantas3").append('<option value="' + datos[i].idplanta + '">' + datos[i].nombre + '</option>');
                }
            }
        });
    });





    $("#formuregistro").validate({
        rules: {
            nombre: {
                required: true

            },
            apellido: {
                required: true
            },
            cuit: {
                required: true
            },
            domicilio: {
                required: true
            },
            correo: {
                email: true,
                required: true
            },
            rsocial: {
                required: true
            },
            condimpositiva: {
                required: true
            },
            usuario: {
                required: true
            },
            clave: {
                required: true
            }
        },
        messages: {
            nombre: {
                required: "Campo obligatorio"


            },
            apellido: {
                required: "Campo obligatorio"
            },
            cuit: {
                required: "Campo obligatorio"
            },
            domicilio: {
                required: "Campo es obligatorio"
            },
            correo: {
                required: "Campo es obligatorio"
            },
            condimpositiva: {
                required: "Campo es obligatorio"
            },
            rsocial: {
                required: "Campo es obligatorio"
            },
            usuario: {
                required: "Campo es obligatorio"
            },
            clave: {
                required: "Campo es obligatorio"
            }
        }
    });
    function getMarkers() {
        $.getJSON("estadistica/getmarkers/", function(json) {




            $.each(json, function(i, item) {
                var myLatlng = new google.maps.LatLng(item.latitud, item.longitud);
                placeMarker(myLatlng, map);


//    alert(item[i].latitud);
//     alert(item[i].longitud);


            });
            //recorriendo datos con un loop for in
//            for(datos in json)//recorremos el json
//            {
//               alert(datos[1].latitud);
//              
//             
//            }
//var myLatlng = new google.maps.LatLng(json[0]['latitud'], json[1]['longitud']);
//                placeMarker(myLatlng, map);
//






//            });



        });
    }

    function getMarkersMenor() {
        $.getJSON("estadistica/getmarkersmenor/", function(json) {

            //$.each(json, function(i, item) {
            var myLatlng = new google.maps.LatLng(item.latitud, item.longitud);
            placeMarker(myLatlng, map);
            //});



        });
    }


    $("#verMapa").click(function() {

        initialize();
        getMarkers();
        getMarkersMenor();

    });
    function cargarEstadistica() {
        var options = {
            chart: {
                renderTo: 'contenedor2',
                type: 'column',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Ventas Generales de Especies',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: []

            },
            yAxis: {
                title: {
                    text: 'CANTIDAD VENDIDA'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#4572A7'
                    }]
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.series.name + '</b><br/>' +
                            this.x + ': ' + this.y;
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.25,
                    borderWidth: 0,
                    colorByPoint: true,
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0,
                enabled: false,
            },
            series: []

        };


        $.getJSON("estadistica/getdatasinfiltro/", function(json) {
            options.xAxis.categories = json[0]['data'];
            options.series[0] = json[1];
            chart = new Highcharts.Chart(options);
        });
    }
    function cargarEstadisticaPorMes() {
        $("#contenedor3").show();
        var options = {
            chart: {
                renderTo: 'contenedor3',
                type: 'column',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Ventas Generales de Especies',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: []

            },
            yAxis: {
                title: {
                    text: 'CANTIDAD VENDIDA'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#4572A7'
                    }]
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.series.name + '</b><br/>' +
                            this.x + ': ' + this.y;
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.25,
                    borderWidth: 0,
                    colorByPoint: true,
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0,
                enabled: false,
            },
            series: []

        };

        var mes = $('#mes').val();
        $.getJSON("estadistica/getdatapormes/" + mes, function(json) {

            options.xAxis.categories = json[0]['data'];
            options.series[0] = json[1];
            chart = new Highcharts.Chart(options);
        });
    }
    $("#resetEst").click(function() {
        $('#ventas1').val('');
        $('#ventas2').val('');
        cargarEstadistica();

    });
    $("#filtMes").click(function() {
        cargarEstadisticaPorMes();

    });

    cargarEstadistica();



});



$(document).ready(function() {
    $("#comboespecies").change(function() {
        $("#comboespecies").val();
        $("#comboespecies option[value='ninguna']").remove();
    });

}

);


var selectTool = (function() {
    var frag = document.createDocumentFragment();
    return {
        hideOpt: function(selectId, optIndex) {
            var sel = document.getElementById(selectId);
            var opt = sel && sel[optIndex];
            console.log(opt);

            if (opt) {
                frag.appendChild(opt);
            }
        },
        showOpt: function(selectId) {
            var sel = document.getElementById(selectId);
            var opt = frag.firstChild;
            if (sel && opt) {
                sel.appendChild(opt);
            }
        }
    }
}());



function showPlantas(str) {

    if (str != 0)
        document.getElementById("comboplantas").disabled = false;
    else
        document.getElementById("comboplantas").disabled = true;


    if (window.XMLHttpRequest)
    {// para IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {// para IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {
        var plantas = xmlhttp.responseText;

        if (xmlhttp.readyState == 1) {

            document.getElementById("comboplantas").innerHTML = "CARGANDO...";

        }


        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            document.getElementById("comboplantas").innerHTML = xmlhttp.responseText;




        }
    }
    xmlhttp.open("GET", "ingreso/listarplantasdeespecie/" + str, true);

    xmlhttp.send();
}



function enableInputColor(str) {

    if (str != 0)
        document.getElementById("combocolores").disabled = false;
    else
        document.getElementById("combocolores").disabled = true;

}

function show(str) {
    if (str == "") {
        document.getElementById("resul").innerHTML = "";
        return;
    }
    if (window.XMLHttpRequest)
    {// codigo para IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {// codigo para IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {

        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            document.getElementById("resul").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", "admin/existecuit/" + str, true);
    xmlhttp.send();
}


function mostrarOcultarEspecie() {
//{
//    if(obj.value == "1") {
//       formAlta.input.style.visibility = (obj.checked) ? 'visible' : 'hidden';
//    }
//    if (document.getElementById())
    if (document.getElementById("chkoir").checked == true) {
        document.getElementById("inputEspecie").style.visibility = "visible";
        document.getElementById("seleccion_especie").disabled = true;
        document.getElementById("input_checked_especie").value = 1;
    }
    else {
        document.getElementById("inputEspecie").style.visibility = "hidden";
        document.getElementById("seleccion_especie").disabled = false;
        document.getElementById("input_checked_especie").value = 0;
    }

}
function mostrarOcultarColor() {
//{
//    if(obj.value == "1") {
//       formAlta.input.style.visibility = (obj.checked) ? 'visible' : 'hidden';
//    }
//    if (document.getElementById())
    if (document.getElementById("colorid").checked == true) {
        document.getElementById("inputColor").style.visibility = "visible";
        document.getElementById("seleccion_color").disabled = true;
        document.getElementById("input_checked_color").value = 1;


    }

    else {

        document.getElementById("inputColor").style.visibility = "hidden";
        document.getElementById("seleccion_color").disabled = false;
        document.getElementById("input_checked_color").value = 0;

    }
}


function verificarStock(urlVenta, idPlanta, idSelectCliente) {

    var inputStock = document.getElementById("inputStock" + idPlanta).value;
    var inputMonto = document.getElementById("inputMonto" + idPlanta).value;
    var selectCliente = document.getElementById("selectCliente" + idSelectCliente).value;
    if (document.getElementById("inputStock" + idPlanta).value === '0') {
        alert("NO TIENE STOCK!!");
    }
    else {
        window.location.href = urlVenta + '/' + inputStock + '/' + inputMonto + '/' + selectCliente;
    }
}
// $(function () {
//            var json = [
//                                {
//                                        "key": "Apples",
//                                        "value": "4"
//                                }, 
//                                {
//                                        "key": "Pears",
//                                        "value": "7"
//                                }, 
//                                {
//                                        "key": "Bananas",
//                                        "value": "9"
//                                }
//                        ];
//                        var processed_json = new Array();
//                        $.map(json, function(obj, i) {
//                                processed_json.push([obj.key, parseInt(obj.value)]);
//                        });
//
//
//                        $('#contenedor').highcharts({
//                                chart: {
//                                        type: 'column'
//                                },
//                                xAxis: {
//                                        type: "category"
//                                },
//                                series: [{
//                                        data: processed_json
//                                }]
//                        });
//                });
//$(function () {
//        $('#contenedor').highcharts({
//            title: {
//                text: 'Monthly Average Temperature',
//                x: -20 //center
//            },
//            subtitle: {
//                text: 'Source: WorldClimate.com',
//                x: -20
//            },
//            xAxis: {
//                categories: <? php echo json_encode ($semanas );  ?> 
//            },
//            yAxis: {
//                title: {
//                    text: 'Temperature (°C)'
//                },
//                plotLines: [{
//                    value: 0,
//                    width: 1,
//                    color: '#808080'
//                }]
//            },
//            tooltip: {
//                valueSuffix: '°C'
//            },
//            legend: {
//                layout: 'vertical',
//                align: 'right',
//                verticalAlign: 'middle',
//                borderWidth: 0
//            },
//            series: [{
//                name: 'Tokyo',
//                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
//            }, {
//                name: 'New York',
//                data:  [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
//            }, {
//                name: 'Berlin',
//                data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
//            }, {
//                name: 'London',
//                data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
//            }]
//        });
//    });


$(function() {

    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd-mm-yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional["es"]);
    $(".datepicker").datepicker({
    });
});



$(function() {

    Highcharts.setOptions({
        colors: ['#000066', '#CC0000', '#006600']
    });

    $("#ventas1, #ventas2").change(function() {

//    $(document).ready(function() {

        var options = {
            chart: {
                renderTo: 'contenedor2',
                type: 'column',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Ventas Generales de Especies por fecha',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: []

            },
            yAxis: {
                title: {
                    text: 'CANTIDAD VENDIDA'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#4572A7'
                    }]
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.series.name + '</b><br/>' +
                            this.x + ': ' + this.y;
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.25,
                    borderWidth: 0,
                    colorByPoint: true
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0,
                enabled: false,
            },
            series: []

        };

        var f1 = $('#ventas1').val();
        var f2 = $('#ventas2').val();
        $.getJSON("estadistica/getdata/" + f1 + '/' + f2, function(json) {
            options.xAxis.categories = json[0]['data'];
            options.series[0] = json[1];
            chart = new Highcharts.Chart(options);
        });
    });


});





//function visitorData (data) {
//   $('#contenedor').highcharts({
//    chart: {
//        type: 'column'
//    },
//    title: {
//        text: 'Average Visitors'
//    },
//    xAxis: {
//        categories: [data[0].data]
//    },
//    yAxis: {
//        title: {
//            text: 'Number of visitors'
//        }
//    },
//    series: data
//  });
//}
//$(document).ready(function() {
// $.ajax({
//    url: 'estadistica/getdata',
//    type: 'GET',
//    async: true,
//    dataType: "json",
//    success: function (data) {
//        visitorData(data);
//       
//    }
//  });
// });
$(function() {

    Highcharts.setOptions({
        colors: ['#000066', '#CC0000', '#006600']
    });

    $("#filtrarMes").click(function() {

        var options = {
            chart: {
                renderTo: 'contenedor4',
                type: 'column',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Especie más y menos vendida por mes de un año determinado',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: []

            },
            yAxis: {
                title: {
                    text: 'CANTIDAD VENDIDA'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#4572A7'
                    }]
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.series.name + '</b><br/>' +
                            this.x + ': ' + this.y;
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.25,
                    borderWidth: 0,
                    colorByPoint: true,
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0,
                enabled: false,
            },
            series: []

        };

        var m = $('#m').val();
        var a = $('#ano').val();

        $.getJSON("estadistica/getDataEspecieMasyMenosVendida/" + m + '/' + a, function(json) {
            options.xAxis.categories = json[0]['data'];
            options.series[0] = json[1];
            chart = new Highcharts.Chart(options);
        });
    });


});


$(document).ready(function() {
    $("#ano").change(function() {
        $("#ano").val();
        $("#ano option[value='0']").remove();
    });

}

);
$(function() {

    Highcharts.setOptions({
        colors: ['#000066', '#CC0000', '#006600']
    });

    $("#filtrarCompras").click(function() {

        var options = {
            chart: {
                renderTo: 'contenedor5',
                type: 'column',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Clientes y sus compras ordenado por los que más compraron por especie',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: ['flores', 'legumbres', 'helechos']

            },
            yAxis: {
                title: {
                    text: 'CANTIDAD VENDIDA'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#4572A7'
                    }]
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.series.name + '</b><br/>' +
                            this.x + ': ' + this.y + " " + 'unidades';
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.25,
                    borderWidth: 0,
                    // colorByPoint: true,
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0,
                enabled: true
            },
            series: [{
                    name: 'damian',
                    data: [10, 0, 0]
                }, {
                    name: 'Jorge',
                    data: [0, 0, 10]


                }]

        };


        $.getJSON("estadistica/getDataComprasClientes", function(json) {
            //options.xAxis.categories = json[0].data;


            chart = new Highcharts.Chart(options);
        });
    });


});


$(document).ready(function() {
    $("#contenedorA").show();
    $("#opcion").change(function() {
        var a = $("#opcion").val();
        if (a == 1) {
            $("#contenedorA").show();
            $("#contenedorB").hide();
            $("#contenedorC").hide();
            $("#contenedorD").hide();
            $("#contenedorE").hide();
        }
        if (a == 2) {
            $("#contenedorB").show();
            $("#contenedorA").hide();
            $("#contenedorC").hide();
            $("#contenedorD").hide();
            $("#contenedorE").hide();
        }
        if (a == 3) {
            $("#contenedorC").show();
            $("#contenedorA").hide();
            $("#contenedorB").hide();
            $("#contenedorD").hide();
            $("#contenedorE").hide();

        }
        if (a == 4) {
            $("#contenedorD").show();
            $("#contenedorA").hide();
            $("#contenedorB").hide();
            $("#contenedorC").hide();
            $("#contenedorE").hide();
        }
        if (a == 5) {
            $("#contenedorE").show();
            $("#contenedorA").hide();
            $("#contenedorB").hide();
            $("#contenedorC").hide();
            $("#contenedorD").hide();
        }



    });

})
$(document).ready(function() {
    $("#check1").click(function() {
        if ($(this).is(":checked")) {
            $(".ck:checkbox:not(:checked)").attr("checked", "checked");
            $("#entreFechas").show();
            if ($("#check2").is(":checked")) {
                $("#check2:checkbox:checked").removeAttr("checked");
                $("#entreFechas2").hide();
            }


        } else {



            $(".ck:checkbox:checked").removeAttr("checked");
            $("#entreFechas").hide();



        }
    });

})
$(document).ready(function() {
    $("#check2").click(function() {



        if ($(this).is(":checked")) {

            $(".ck:checkbox:not(:checked)").attr("checked", "checked");

            $("#entreFechas2").show();
            if ($("#check1").is(":checked")) {
                $("#check1:checkbox:checked").removeAttr("checked");
                $("#entreFechas").hide();
            }



        } else {

            $("#check2:checkbox:checked").removeAttr("checked");
            $("#entreFechas2").hide();
        }
    });

})

$(function() {

    Highcharts.setOptions({
        colors: ['#000066', '#CC0000', '#006600']
    });

    $("#ventas3, #ventas4").change(function() {

//    $(document).ready(function() {

        var options = {
            chart: {
                renderTo: 'contenedor2',
                type: 'column',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Ventas Generales de socios entre fechas',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: []

            },
            yAxis: {
                title: {
                    text: 'CANTIDAD VENDIDA'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#4572A7'
                    }]
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.series.name + '</b><br/>' +
                            this.x + ': ' + this.y;
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.25,
                    borderWidth: 0,
                    colorByPoint: true
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0,
                enabled: false,
            },
            series: []

        };

        var fecha1 = $('#ventas3').val();

        var fecha2 = $('#ventas4').val();

        var socio = $('#combosocios').val();

        $.getJSON("estadistica/getVentasPorSocioEntreFechas/" + socio + '/' + fecha1 + '/' + fecha2, function(json) {
            options.xAxis.categories = json[0]['data'];
            options.series[0] = json[1];
            chart = new Highcharts.Chart(options);
        });
    });


});
