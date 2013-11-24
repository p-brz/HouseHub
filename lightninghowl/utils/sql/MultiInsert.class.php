<?php

namespace lightninghowl\utils\sql;

class MultiInsert extends SqlInstruction{
	private $columns;
	private $columnsValues;
	
	public function __construct(){
		$this->columns = array();
		$this->columnsValues = array();
	}
	
	public function setColumns(){
		$columns = func_get_args();
		
		foreach($columns as $column){
			if(is_string($column)){
				$this->columns[] = $column;
			}
		}
	}
	
	public function addColumnValues(){
		if(func_num_args() == count($this->columns)){
			$values = func_get_args();
			$rowData = array();
			for($i = 0; $i < func_num_args(); $i++){
				$value = $values[$i];
				if(is_scalar($value)){
					if(is_string($value) and !empty($value)){
						$value = addslashes($value);
						$rowData[] = "'$value'";
					}
					else if(is_bool($value)){
						$rowData[] = $value ? 'TRUE' : 'FALSE';
					}
					else if($value !== ''){
						$rowData[] = $value;
					}
					else{
						$rowData[] = NULL;
					}
				}
			}
			$this->columnsValues[] = $rowData;
			
		}else{
			throw new Exception('Multi query construction failure. The columns number does not match the values count.');
		}
	}
	
	public function getInstruction(){
		$this->sql  = "INSERT INTO {$this->entity} (";
		$this->sql .= implode(', ', $this->columns);
		$this->sql .= ") VALUES ";
		$rows = array();
		foreach($this->columnsValues as $values){
			$row = '('.implode(', ', $values).')';
			$rows[] = $row;
		}
		
		$this->sql .= implode(', ', $rows);
		return $this->sql;
		
	}
}

?>