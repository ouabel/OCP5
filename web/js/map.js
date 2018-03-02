function initMap() {

    $.when($.getJSON(dataUrl)).done(function(data){
        if (data.length > 0) {
            var mapDiv = document.getElementById('map');
            var map = new google.maps.Map(mapDiv, {
                zoom: 8,
                center: {lat: 29.285693, lng: 3.581005}
            });
            var bounds = new google.maps.LatLngBounds();

            var infowindow = new google.maps.InfoWindow();
            data.forEach(function(profile){
                var marker = new google.maps.Marker({
                    title: profile.name,
                    position: profile.position,
                    map: map,
                    icon: baseUrl + '/icon/yellow.png'
                });
                bounds.extend(marker.getPosition());
                map.fitBounds(bounds);
                var listener = google.maps.event.addListener(map, "idle", function() {
                    if (map.getZoom() > 12) map.setZoom(12);
                    google.maps.event.removeListener(listener);
                });
                profileDiv = document.getElementById('profile-' + profile.id);

                marker.addListener('click', function() {
                    $('.panel-primary').attr('class', 'panel panel-default');
                    $('#profile-' + profile.id).attr('class', 'panel panel-primary');

                    if (!$('.profiles-list').hasClass('hidden-xs')) {
                        $('html, body').animate({
                            scrollTop: ($('#profile-' + profile.id).offset().top - $('#filter_form_container').outerHeight( true ) - 12)
                        }, 500);
                    } else {

                        var content =  '<strong>' + profile.name + '</strong><br>' + profile.specialty + ' à ' + profile.district +'<br><a href="' + profile.link + '">Détails ...</a>' ;

                        infowindow.setContent(content);
                        infowindow.open(map, marker);

                    }
                });

                marker.addListener('mouseover', function() {
                    this.setIcon(baseUrl + '/icon/blue.png');
                });

                marker.addListener('mouseout', function() {
                    this.setIcon(baseUrl + '/icon/yellow.png');
                });

                google.maps.event.addDomListener(profileDiv, 'mouseover', function() {
                    marker.setIcon(baseUrl + '/icon/blue.png');
                });

                google.maps.event.addDomListener(profileDiv, 'mouseout', function() {
                    infowindow.close(map, marker);
                    marker.setIcon(baseUrl + '/icon/yellow.png');
                });
            });
        }
    });

}