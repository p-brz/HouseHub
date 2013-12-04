<?php

namespace househub\users\rights;

abstract class AbstractRights{
	
	protected $rights;
	
	public function getRights() { return $this->rights; }
	
	public function verifyRights($entityId){
		return (in_array($entityId, $this->rights));
	}
	
}

?>