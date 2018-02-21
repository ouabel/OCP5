function initMap() {

	var mapDiv = document.getElementById('map');

	var map = new google.maps.Map(mapDiv, {
		zoom: 8,
		center: {lat: 29.285693, lng: 3.581005}
	});
	var bounds = new google.maps.LatLngBounds();
	
	$.when($.getJSON(dataUrl)).done(function(data){
		var infowindow = new google.maps.InfoWindow();
		data.forEach(function(profile){
			var marker = new google.maps.Marker({
				title: profile.name,
				position: profile.position,
				map: map,
				icon: '/icon/4.png'
			});
			bounds.extend(marker.getPosition());
			map.fitBounds(bounds);
			var listener = google.maps.event.addListener(map, "idle", function() { 
				if (map.getZoom() > 12) map.setZoom(12); 
				google.maps.event.removeListener(listener); 
			});

			marker.addListener('click', function() {
				infowindow.setContent(profile.name);
				infowindow.open(map, marker);
			});
			
			profileDiv = document.getElementById('profile-' + profile.id);

			google.maps.event.addDomListener(profileDiv, 'mouseover', function() {
				marker.setIcon('/icon/3.png');
			});

			google.maps.event.addDomListener(profileDiv, 'mouseout', function() {
				infowindow.close(map, marker);
				marker.setIcon('/icon/4.png');
			});
		});
	});

}