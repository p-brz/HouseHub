<?php

namespace lightninghowl\utils\sql;

final class SelectQuery extends SqlInstruction{
	private $columns;
	private $joins;
	
        public function __construct(){
            $this->columns = array();
            $this->joins = array();
        }
        
	public function addColumn($column){
		$this->columns[] = $column;
	}
	
	public function addJoin($join){
		$this->joins[] = $join;
	}
	
	public function getInstruction(){
		$this->sql  = 'SELECT ';
		$this->sql .= implode(',', $this->columns);
		$this->sql .= ' FROM '.$this->entity;
		
		if(!empty($this->joins)){
			foreach($this->joins as $join){
				$this->sql .= ' '.$join->dump();
			}
		}
		
		if($this->criteria){
			$expression = $this->criteria->dump();
			if($expression){
				$this->sql .= ' WHERE '.$expression;
			}
			
			$order  = $this->criteria->getProperty('order');
			$limit  = $this->criteria->getProperty('limit');
			$offset = $this->criteria->getProperty('offset');
			
			if($order){
				$this->sql .= ' ORDER BY '.$order;
			}
			
			if($limit){
				$this->sql .= ' LIMIT '.$limit;
			}
			
			if($offset){
				$this->sql .= ' OFFSET '.$offset;
			}
		}
		
		return $this->sql;
	}
}