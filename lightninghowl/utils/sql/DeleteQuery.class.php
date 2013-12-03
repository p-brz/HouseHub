<?php

namespace lightninghowl\utils\sql;

/**
 * Represents a delete query from the SQL context.
 * 
 * @author Alison Bento "Lykaios" <alisonlks@outlook.com>
 * @version 1.0.1
 */
final class DeleteQuery extends SqlInstruction {

    public function getInstruction() {
        $this->sql = "DELETE FROM {$this->entity}";
        if ($this->criteria) {
            $expression = $this->criteria->dump();
            if ($expression) {
                $this->sql .= ' WHERE ' . $expression;
            }
        }

        return $this->sql;
    }

}

?>