<?php 
require_once("places.php");

Class AssignAddresses {
	
	private $usedAddresses = array();
	
	const MAX_NO_ADDR = 4096; // fiindca avem subnet de /20
	
	function parser($results) {
		$places = new Places();
		
		$results = $this->sortResults($results);
		//print_r($results);
		
		$i = 0;
		foreach($results as $result) {
			
			$lat = $result['geometry']['location']['lat'];
			$lng = $result['geometry']['location']['lng'];
			$name = $result['name'];
			$reference = $result['reference'];
			
			$tokens = explode(" ", $name);
			if(count($tokens) == 3) {// address request
				$this->addAddress($i, $lng, $lat, $tokens[2]);
				$i++;
			} else {
				$this->deleteAddress($lng, $lat);
			}
			$places->deletePlace($reference);
			
			
		}
		//print_r($this->usedAddresses);
		return $this->usedAddresses;
		
	}
	
	function addAddress($reference, $lng, $lat, $mask) {
		
		$usedsize = count($this->usedAddresses);
		
		$lengthtoinsert = $this->maskToLength($mask);
		
		if($usedsize == 0) {
			$this->insertAddress($reference, 0, $lengthtoinsert, $lng, $lat);
			$this->sortAddresses();
			return;
		}
		$start = reset($this->usedAddresses);
		$startpoint = $start['start'];
		
		if($startpoint != 0 && $startpoint >= $lengthtoinsert) {
			$this->insertAddress($reference, 0, $lengthtoinsert, $lng, $lat);
			$this->sortAddresses();
			return;			
		}
		
		$startlength = $start['length'];
		
		if($usedsize == 1) {
			$this->insertAddress($reference, ($startpoint+$startlength), $lengthtoinsert, $lng, $lat);
			$this->sortAddresses();
			return;
		}
		
		$length = count($this->usedAddresses);
		for($i = 0; $i < $length-1; $i++) {
			$cur = current($this->usedAddresses);
			$curstart = $cur['start'];
			$curlength = $cur['length'];
			$next = next($this->usedAddresses);
			$nextstart = $next['start'];
			
			if(($curstart+$curlength) < $nextstart) {
				$space = $nextstart - ($curstart+$curlength);
				if($space >= $lengthtoinsert) {
					$this->insertAddress($reference, ($curstart+$curlength), $lengthtoinsert, $lng, $lat);
					$this->sortAddresses();
					return;
				}
			}
		}
		
		$last = current($this->usedAddresses);
		$laststart = $last['start'];
		$lastlength = $last['length'];
		if(($laststart+$lastlength) < self::MAX_NO_ADDR) {
			$space = self::MAX_NO_ADDR - ($laststart+$lastlength);
			if($space >= $lengthtoinsert) {
				$this->insertAddress($reference, ($laststart+$lastlength), $lengthtoinsert, $lng, $lat);
				$this->sortAddresses();
				return;
			}
		}
		
		echo "Nu e destul loc!!!";
	}
	
	function maskToLength($mask) {
		$value = substr($mask, 1);
		return pow(2, 32-$value);
	}
	
	function deleteAddress($lng, $lat) {
		$reference = $this->searchAddresses($lng, $lat);
		unset($this->usedAddresses[$reference]);
	}
	
	function searchAddresses($lng, $lat) {
		foreach($this->usedAddresses as $key=>$value) {
			if($lng == $value['lng'] && $lat == $value['lat']) {
				return $key;
			}
		}
	}
	
	
	function insertAddress($reference, $start, $length, $lng, $lat) {
		$this->usedAddresses[$reference]['start'] = $start;
		$this->usedAddresses[$reference]['length'] = $length;
		$this->usedAddresses[$reference]['lng'] = $lng;
		$this->usedAddresses[$reference]['lat'] = $lat;
		$name = "HACK ADDR ";
		
		$mask = 32 - log($length, 2);
		$startiplong = ip2long("225.240.0.0") + $start;
		$ip = long2ip($startiplong)."/".$mask;
		$name.=$ip;
		
		$this->usedAddresses[$reference]['name'] = $name;
		
	}
	
	function sortResults($results) {
		uasort($results, function ($a, $b) {
			$diflat = $b['geometry']['location']['lat'] - $a['geometry']['location']['lat'];
			if($diflat < 0) {
				return -1;
			} else if($diflat > 0) {
				return 1;
			}
			
			$diflng = $b['geometry']['location']['lng']- $a['geometry']['location']['lng'];
			if($diflng < 0) {
				return -1;
			} else if($diflat > 0) {
				return 1;
			}
			
			return strcmp($b['name'], $a['name']);
		});
		
		return $results;
	}
	
	function sortAddresses() {
		uasort($this->usedAddresses, function ($a, $b) {
			return $a['start'] - $b['start'];
		});
	}
	
	
}