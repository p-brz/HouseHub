<?php

namespace lightninghowl\utils\date;

class Date{
	
	private $day;
	private $mouth;
	private $year;
	
	private $hours;
	private $minutes;
	private $seconds;
	
	public function getDay() { return $this->day; } 
	public function getMouth() { return $this->mouth; } 
	public function getYear() { return $this->year; } 
	public function getHours() { return $this->hours; } 
	public function getMinutes() { return $this->minutes; } 
	public function getSeconds() { return $this->seconds; } 
	
	public function setDay($x) { $this->day = $x; } 
	public function setMouth($x) { $this->mouth = $x; } 
	public function setYear($x) { $this->year = $x; } 
	public function setHours($x) { $this->hours = $x; } 
	public function setMinutes($x) { $this->minutes = $x; } 
	public function setSeconds($x) { $this->seconds = $x; } 
	
	public function __construct($databaseDate){
		$dateArray = explode(' ', $databaseDate);
		
		$yMdArray = explode('-', $dateArray[0]);
		$this->year		= $yMdArray[0];
		$this->mouth	= $yMdArray[1];
		$this->day		= $yMdArray[2];
		
		$hourArray = explode(':', $dateArray[1]);
		$this->hours	= $hourArray[0];
		$this->minutes 	= $hourArray[1];
		$this->seconds 	= $hourArray[2];
	}
	
}

?>