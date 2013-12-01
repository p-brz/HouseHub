<?php

namespace lightninghowl\utils\date;

class DMYFormat implements DateMask{
	
	private $date;
	
	public function __construct(Date $date){
		$this->date = $date;
	}
	
	public function getMask(){
		$date = $this->date;
		return "{$date->getDay()}/{$date->getMouth()}/{$date->getYear()} {$date->getHours()}:{$date->getMinutes()}:{$date->getSeconds()}";
	}
	
}

?>