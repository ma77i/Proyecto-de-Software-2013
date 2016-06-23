/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function initialize() {
    google.maps.visualRefresh = true;


    var latitud = document.getElementById("latitud").value;
    var longitud = document.getElementById("longitud").value;
    var mapOptions = {
        zoom: 10,
        center: new google.maps.LatLng(latitud, longitud),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };


    var map = new google.maps.Map(document.getElementById('map'),
            mapOptions);

    google.maps.event.addListener(map, 'click', function(e) {
        placeMarker(e.latLng, map);
        obtenerLatitudLongitud(e.latLng);
    });
}

function obtenerLatitudLongitud(e) {
    document.getElementById("latitud").value = e.lat();
    document.getElementById("longitud").value = e.lng();
}


function placeMarker(position, map) {

    var marker = new google.maps.Marker({
        position: position,
        map: map
    });
    map.panTo(position);


}

google.maps.event.addDomListener(window, 'load', initialize);
