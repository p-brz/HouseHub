<?php

namespace lightninghowl\utils\date;

class YMDFormat implements DateMask{
	
	private $date;
	
	public function __construct(Date $date){
		$this->date = $date;
	}
	
	public function getMask(){
		$date = $this->date;
		return "{$date->getYear()}/{$date->getMouth()}/{$date->getDay()} {$date->getHours()}:{$date->getMinutes()}:{$date->getSeconds()}";
	}
	
}

?>