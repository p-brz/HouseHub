<?php

namespace lightninghowl\utils\sql;

class InnerJoin extends Join{
	
	public function dump(){
		return "INNER JOIN {$this->entity} ON ".$this->criteria->dump();
	}
	
}

?>