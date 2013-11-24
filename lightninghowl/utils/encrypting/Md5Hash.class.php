<?php

namespace lightninghowl\utils\encrypting;

class Md5Hash implements EncrypedData{
	
	public function encrypt($data){
		return md5($data);
	}
	
}

?>