<?php

require_once("assignaddresses.php");

class Map {
	
	private $index = 0;
	
	function __construct() {
	}

	function addMarker($lat, $lng, $name){
		 
		$result="";
		 
		$result.="lat=parseFloat($lat);";
		$result.="lng=parseFloat($lng);";
		$result.="point = new google.maps.LatLng(lat, lng);";
                $result.="html = \"$name\";";
		$result.="label = \"$this->index\";";
		$result.="createMarker(point, label, html);";
		$this->index++;
		
		return $result;
		 
	}
	
	function parseArray($array) {
		foreach($array as $element) {
			echo $this->addMarker($element['lat'], $element['lng'], $element['name']);
		}
	}
        /*
	function parseXml($uri) {
		$xmlreader = new XMLReader();
		$xmlreader->open($uri);
		
		
		
		while ($xmlreader->read() && $xmlreader->name !== 'marker');
		while($xmlreader->name === "marker") {
			$lat = trim($xmlreader->getAttribute('lat'));
			$lng = trim($xmlreader->getAttribute('lng'));
			$query = trim($xmlreader->getAttribute('query'));
			$this->addressAssigner->parser($query);
			$label = trim($xmlreader->getAttribute('label'));
			
			echo $this->addMarker($lat, $lng, $query, $label)."\n";
			$xmlreader->next("marker");
		}
		$xmlreader->close();
	}*/
	
}