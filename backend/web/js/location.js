$(document).ready(function () {
    'use strict';
    (function() {

        var markersData = [
            {
                lat: 40.769296,
                lng: 43.8464097,
            },
            {
                lat: 40.79226,
                lng: 43.844971,

            },
            {
                lat: 40.799026,
                lng: 43.8464971,
            } // don’t insert comma in last item
        ];
        if(!!navigator.geolocation) {

            var map;

            var mapOptions = {
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.TERRAIN
            };

            map = new google.maps.Map(document.getElementById('google_canvas'), mapOptions);



            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocate = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                var radius_circle = new google.maps.Circle({
                    center: geolocate,
                    radius: 5000,
                    clickable: false,
                    map: map
                });
                console.log(radius_circle);
                var bounds = new google.maps.LatLngBounds();

                // For loop that runs through the info on markersData making it possible to createMarker function to create the markers
                for (var i = 0; i < markersData.length; i++){

                    var address_lat_lng = new google.maps.LatLng(markersData[i].lat, markersData[i].lng);
                    var distance_from_location = google.maps.geometry.spherical.computeDistanceBetween(address_lat_lng, geolocate);
                    if(distance_from_location<=5000){
                        var marker1 = new google.maps.Marker({
                            map: map,
                            position: address_lat_lng
                        });
                    }

                    // Marker’s Lat. and Lng. values are added to bounds variable
                    bounds.extend(address_lat_lng);
                }

                // Finally the bounds variable is used to set the map bounds
                // with API’s fitBounds() function
                map.fitBounds(bounds);

                var marker = new google.maps.Marker({
                    animation: google.maps.Animation.BOUNCE,
                    map: map,
                    position:geolocate
                });
                var infowindow = new google.maps.InfoWindow({
                    content: '<h1>this is my position</h1>'
                });

                map.setCenter(geolocate);

            });

        } else {
            document.getElementById('google_canvas').innerHTML = 'No Geolocation Support.';
        }

    })();
});
