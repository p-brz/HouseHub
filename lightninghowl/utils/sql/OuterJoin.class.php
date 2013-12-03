<?php

namespace lightninghowl\utils\sql;

class OuterJoin extends Join {

    const LEFT = 'LEFT';
    const RIGHT = 'RIGHT';

    private $side;

    public function setSide($side) {
        $this->side = strtoupper($side);
    }

    public function dump() {
        return $this->side ? "{$this->side} OUTER JOIN {$this->entity} ON " . $this->criteria->dump() : "LEFT OUTER JOIN {$this->entity} ON " . $this->criteria->dump();
    }

}
