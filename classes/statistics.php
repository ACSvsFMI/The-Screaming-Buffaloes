<?php

Class Statistics {
	
	const MAX_NO_ADDR = 4096; // fiindca avem subnet de /20
	
	function showStatistics($array) {
		$result="Ipotetic vorbind, ar mai incapea... ";
		for($i = 24; $i <= 31; $i++) {
			$result.= "<p>";
			$result.= $this->howManyDoTheyFit($this->maskToLength($i), $array);
			$result.= " adrese /$i";
			$result.= "</p>";
		}
		echo $result;
	}
	
	function howManyDoTheyFit($length, $array) {
		$contor = 0;
		
		
		$start = reset($array);
		$startpoint = $start['start'];
		
		$currentpointboss = $length;
		while($startpoint != 0 && $startpoint >= $currentpointboss) {
			$contor++;
			$currentpointboss += $length;
		}
		
		$size = count($array);
		for($i = 0; $i < $size-1; $i++) {
			$cur = current($array);
			$curstart = $cur['start'];
			$curlength = $cur['length'];
			$next = next($array);
			$nextstart = $next['start'];
			
			if(($curstart+$curlength) < $nextstart) {
				$space = $nextstart - ($curstart+$curlength);
				while($space >= $length) {
					$contor++;
					$space -= $length;
				}
			}
		}
		
		$last = current($array);
		$laststart = $last['start'];
		$lastlength = $last['length'];
		if(($laststart+$lastlength) < self::MAX_NO_ADDR) {
			$space = self::MAX_NO_ADDR - ($laststart+$lastlength);
			while($space >= $length) {
				$contor++;
				$space -= $length;
			}
		}
		
		return $contor;
	}
	
	
	function maskToLength($mask) {
		return pow(2, 32-$mask);
	}
}