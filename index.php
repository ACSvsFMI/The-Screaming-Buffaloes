<?php
require_once("classes/map.php");
require_once("classes/statistics.php");
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
	font-family: 'Open Sans', sans-serif;
	height: 100%;
	margin: 0;
	padding: 0
}

#map_canvas {
	float: left;
	width: 100%;
	height: 100%;
	z-index: 2;
}

#statistics {
	width: 400px;
	display: none;
}

#button {
	position: absolute;
	top: 10px;
	left: 50%;
	height: 100px;
	width: 100px;
	z-index: 5;
}

#button {
	display:block;
	width:250px;
	height:50px;
	top: 20px;
	left: 50%;
	margin: 0 0 0 -125px;
	text-indent:-9999px;
	z-index: 5;
}
#button a {
	margin:auto;
	display:block;
	width:250px;
	height:100%;
	background:url(images/statistics.png) no-repeat top left;
	outline:none;
	-webkit-box-shadow: 9px 8px 14px rgba(50, 50, 50, 0.7);
-moz-box-shadow:    9px 8px 14px rgba(50, 50, 50, 0.7);
box-shadow:         9px 8px 14px rgba(50, 50, 50, 0.7);
}
#button a:hover {
	margin:auto;
	background-position:0 -50px;
}

#statistics ul {
	list-style: none;
	margin: 0;
	padding: 0;
}

#statistics ul li {
	display: block;
	padding: 8px 0px 8px 10px;
	xbackground-color: #000000;
	background-color: #DDD;
	color: #333;
	font-size: 11px;
	line-height: 20px;
	font-weight: 700;
	letter-spacing: -0.5px;
	text-transform: uppercase;
	text-shadow: 0px 1px 0px rgba(255, 255, 255, 0.5);
	xfilter: dropshadow(color=#000000, offx=0, offy=-1);
	-webkit-transition: all 200ms ease;
	-moz-transition: all 200ms ease;
	-o-transition: all 200ms ease;
	transition: all 200ms ease;
}

#statistics ul li:hover {
	background-color: #111;
	color: #80A535;
	text-decoration: none;
	text-shadow: none;
}

</style>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpb6x0w0jD0Dm23bZwUkMSOntIq5-U2xA&sensor=true"></script>
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery.fancybox.js"></script>
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
<link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css" media="screen" />
<script type="text/javascript">
	$(document).ready(function() {
		$('.fancybox').fancybox();
	});
</script>
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

}


	function myclick(i) {
		google.maps.event.trigger(gmarkers[i], "click");
	}

	function initialize() {

	var myOptions = {
		zoom: 15,
		center: new google.maps.LatLng(41.90077578469788, 12.453217506408691),
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

		$places->addPlace(41.903139448813754, 12.453217506408691,"HACK REQ /30");
		$places->addPlace(41.90216524650067, 12.457273006439209,"HACK REQ /27");
		$places->addPlace(41.90216524650067, 12.453432083129883,"HACK REQ /29");
		$places->addPlace(41.90652508551971, 12.454376220703125,"HACK REQ /29");
		$places->addPlace(41.90216524650067, 12.457273006439209,"HACK DELETE");
		$places->addPlace(41.90077578469788, 12.455835342407227,"HACK REQ /28");
		$places->addPlace(41.90216524650067, 12.457273006439209,"HACK REQ /25");
		
		$results = $places->searchPlaces(41.90077578469788, 12.453217506408691, 10000, "HACK");
		
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
	<div id="button">
		<a class="fancybox" href="#statistics" title="Statistics">Statistics</a>
	</div>
	<div id="map_canvas"></div>
	<div id="statistics">
	
	<?php 
		$statistics = new Statistics();
		
		$statistics->showStatistics($addresses);
	?>
	
	</div>
</body>
</html>