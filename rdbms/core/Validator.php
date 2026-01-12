<?php
class Validator {
    // Validate a row against a schema
    public function validateRow($row, $schema) {
        foreach($schema['columns'] as $colName => $colDef) {
            // Check column exists
            if(!array_key_exists($colName, $row)) {
                throw new Exception("Missing column '$colName'");
            }

            $value = $row[$colName];
            $type = $colDef['type'];

            // Check type
            if($type === 'INTEGER' && !is_int($value)) {
                throw new Exception("Column '$colName' must be INTEGER");
            }
            if($type === 'STRING' && !is_string($value)) {
                throw new Exception("Column '$colName' must be STRING");
            }
            if($type === 'BOOLEAN' && !is_bool($value)) {
                throw new Exception("Column '$colName' must be BOOLEAN");
            }
        }
        return true;
    }

    // Check primary key uniqueness
    public function validatePrimaryKey($row, $tableRows, $primaryKey) {
        foreach($tableRows as $existingRow) {
            if($existingRow[$primaryKey] === $row[$primaryKey]) {
                throw new Exception("Duplicate primary key '{$primaryKey}' value");
            }
        }
        return true;
    }

    // Check unique constraints
    public function validateUnique($row, $tableRows, $uniqueColumns) {
        foreach($uniqueColumns as $col) {
            foreach($tableRows as $existingRow) {
                if($existingRow[$col] === $row[$col]) {
                    throw new Exception("Duplicate unique key '{$col}' value");
                }
            }
        }
        return true;
    }
}
?>
