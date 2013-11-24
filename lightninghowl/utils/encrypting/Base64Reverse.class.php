<?php

namespace lightninghowl\utils\encrypting;

class Base64Reverse implements EncryptedData, DecryptedData{
	
	public function encrypt($data){
		$str = $data;
		for($i=0; $i < 2;$i++) {
		$str = strrev(base64_encode($str));
		}
		return $str;
	}
	
	public function decrypt($data){
		$str = $data;
		for($i=0; $i < 2;$i++){
			$str = base64_decode(strrev($str));
		}
		return $str;
	}
	
}

?>