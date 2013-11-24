<?php

namespace lightninghowl\utils\encrypting;

class Sha1Hash implements EncryptedData{
	
	public function encrypt($data){
		return sha1($data);
	}
	
}

?>