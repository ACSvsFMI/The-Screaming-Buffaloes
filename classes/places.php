<?php 

class Places {

	
	function ProcessCurl($URL, $fieldString){ //Initiate Curl request and send back the result
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldString);
		$result = curl_exec ($ch);
		if (curl_errno($ch)) {
			print curl_error($ch);
		} else {
			curl_close($ch);
		}
		return $result;
	}

	
	function addPlace($lat, $long, $name) {
		$jsonpost = '{
			"location": {
				"lat": '.$lat.',
				"lng": '.$long.'
			},
			"accuracy": 50,
			"name": "'.$name.'",
			"types": ["accounting"],
			"language": "en-AU"
		}';
		
		$url = "https://maps.googleapis.com/maps/api/place/add/json?sensor=false&key=AIzaSyDpb6x0w0jD0Dm23bZwUkMSOntIq5-U2xA";
	
		$result = json_decode($this->ProcessCurl($url, $jsonpost), true);
		$reference = $result['reference'];
		
	}
	
	function deletePlace($reference) {
		$jsonpost = '{
			"reference": "'.$reference.'"
					}';
		$url = "https://maps.googleapis.com/maps/api/place/delete/json?sensor=false&key=AIzaSyDpb6x0w0jD0Dm23bZwUkMSOntIq5-U2xA";
		
		$result = $this->ProcessCurl($url, $jsonpost);
		
	}
	
	function searchPlaces($base_lat, $base_lng, $rad, $search_string){
		 
		$url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=$base_lat,$base_lng&radius=$rad&name=$search_string&sensor=false&key=AIzaSyDpb6x0w0jD0Dm23bZwUkMSOntIq5-U2xA";
		$jsonpost = "";
		
		$results = json_decode($this->ProcessCurl($url, $jsonpost), true);
		
		$results = $results['results'];
		
		return $results;

	}
       
       
}