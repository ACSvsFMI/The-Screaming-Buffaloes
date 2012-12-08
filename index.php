<?php
require_once("classes/map.php");
require_once("classes/places.php");
require_once("classes/assignaddresses.php");
error_reporting(E_ERROR);
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
html {
	height: 100%
}

body {
	height: 100%;
	margin: 0;
	padding: 0
}

#map_canvas {
	height: 100%
}
</style>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpb6x0w0jD0Dm23bZwUkMSOntIq5-U2xA&sensor=true"></script>

<script type="text/javascript">
    var side_bar_html = ""; 
    var gmarkers = []; 
    var map = null;

	function createMarker(latlng, name, html) {
		var contentString = html;
		var marker = new google.maps.Marker({
			position: latlng,
			map: map,
			zIndex: Math.round(latlng.lat()*-100000)<<5
	});

	google.maps.event.addListener(marker, 'click', function() {
		infowindow.setContent(contentString); 
		infowindow.open(map,marker);
	});

	gmarkers.push(marker);

	side_bar_html += '<a href="javascript:myclick(' + (gmarkers.length-1) + ')">' + name + '<\/a><br>';
}


	function myclick(i) {
		google.maps.event.trigger(gmarkers[i], "click");
	}

	function initialize() {

	var myOptions = {
		zoom: 8,
		center: new google.maps.LatLng(43.907787,-79.359741),
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		navigationControl: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

	google.maps.event.addListener(map, 'click', function() {
		infowindow.close();
	});

	<?php
		$places = new Places();

		$places->addPlace(35.454364,22.099288,"HACK REQ /30");
		$places->addPlace(35.464364,22.089288,"HACK REQ /27");
		$places->addPlace(35.466364,22.086288,"HACK REQ /29");
		$places->addPlace(35.468364,22.092288,"HACK REQ /29");
		$places->addPlace(35.464364,22.089288,"HACK DELETE");
		$places->addPlace(35.464364,22.099288,"HACK REQ /28");
		$results = $places->searchPlaces(35.454364, 22.099288, 10000, "HACK");
		
		$assigner = new AssignAddresses();
		$addresses = $assigner->parser($results);
		
		$map = new Map();
		$map->parseArray($addresses);
	?>
	
  	}

	var infowindow = new google.maps.InfoWindow({ 
  		size: new google.maps.Size(150,50)
	});
    
</script>


</head>
<body onload="initialize()">
	<div id="map_canvas" style="width: 70%; height: 100%"></div>
	<div id="side_bar" style="width: 30%"></div>
</body>
</html>