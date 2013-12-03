<?php

namespace lightninghowl\utils\encrypting;

class Md5Hash implements EncryptedData{
	
	public function encrypt($data){
		return md5($data);
	}
	
}