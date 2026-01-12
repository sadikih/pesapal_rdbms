<?php
require_once __DIR__ . '/Storage.php';
require_once __DIR__ . '/Validator.php';
require_once __DIR__ . '/Index.php';

class Executor {
    private $storage;
    private $validator;
    private $indexer;

    public function __construct() {
        $this->storage = new Storage();
        $this->validator = new Validator();
        $this->indexer = new Index();
    }

    // CREATE operation
    public function create($tableName, $row) {
        // Load schema
        $schema = $this->storage->loadSchema($tableName);
        if(!$schema) {
            throw new Exception("Table '$tableName' does not exist.");
        }

        // Load existing rows
        $rows = $this->storage->loadTable($tableName);

        // Validate row against schema
        $this->validator->validateRow($row, $schema);
        $this->validator->validatePrimaryKey($row, $rows, $schema['primary_key']);
        if(isset($schema['unique'])) {
            $this->validator->validateUnique($row, $rows, $schema['unique']);
        }

        // Add row
        $rows[] = $row;
        $this->storage->saveTable($tableName, $rows);

        // Update indexes
        $this->indexer->saveIndex($tableName, $schema['primary_key'], $this->indexer->buildIndex($rows, $schema['primary_key']));
        if(isset($schema['unique'])) {
            foreach($schema['unique'] as $col) {
                $this->indexer->saveIndex($tableName, $col, $this->indexer->buildIndex($rows, $col));
            }
        }

        echo "Row added successfully!\n";
    }

    // READ operation
    public function read($tableName, $conditions = []) {
        // Load schema
        $schema = $this->storage->loadSchema($tableName);
        if(!$schema) {
            throw new Exception("Table '$tableName' does not exist.");
        }

        // Load rows
        $rows = $this->storage->loadTable($tableName);

        // Filter rows if conditions provided
        if(!empty($conditions)) {
            $filtered = [];
            foreach($rows as $row) {
                $match = true;
                foreach($conditions as $col => $value) {
                    if(!isset($row[$col]) || $row[$col] !== $value) {
                        $match = false;
                        break;
                    }
                }
                if($match) {
                    $filtered[] = $row;
                }
            }
            return $filtered;
        }

        return $rows; // Return all rows if no conditions
    }

    // UPDATE operation
    public function update($tableName, $conditions, $newData) {
        // Load schema
        $schema = $this->storage->loadSchema($tableName);
        if(!$schema) {
            throw new Exception("Table '$tableName' does not exist.");
        }

        // Load existing rows
        $rows = $this->storage->loadTable($tableName);
        $updated = 0;

        foreach($rows as $index => $row) {
            $match = true;
            foreach($conditions as $col => $value) {
                if(!isset($row[$col]) || $row[$col] !== $value) {
                    $match = false;
                    break;
                }
            }

            if($match) {
                $updatedRow = array_merge($row, $newData);

                // Validate updated row
                $this->validator->validateRow($updatedRow, $schema);

                // Exclude current row when validating primary key and unique constraints
                $rowsExcludingCurrent = $rows;
                unset($rowsExcludingCurrent[$index]);
                $this->validator->validatePrimaryKey($updatedRow, $rowsExcludingCurrent, $schema['primary_key']);
                if(isset($schema['unique'])) {
                    $this->validator->validateUnique($updatedRow, $rowsExcludingCurrent, $schema['unique']);
                }

                $rows[$index] = $updatedRow;
                $updated++;
            }
        }

        // Save updated rows
        $this->storage->saveTable($tableName, $rows);

        // Rebuild indexes
        $this->indexer->saveIndex($tableName, $schema['primary_key'], $this->indexer->buildIndex($rows, $schema['primary_key']));
        if(isset($schema['unique'])) {
            foreach($schema['unique'] as $col) {
                $this->indexer->saveIndex($tableName, $col, $this->indexer->buildIndex($rows, $col));
            }
        }

        echo "$updated row(s) updated successfully!\n";
    }

    // DELETE operation
public function delete($tableName, $conditions) {
    // Load schema
    $schema = $this->storage->loadSchema($tableName);
    if(!$schema) {
        throw new Exception("Table '$tableName' does not exist.");
    }

    // Load existing rows
    $rows = $this->storage->loadTable($tableName);
    $deleted = 0;

    foreach($rows as $index => $row) {
        $match = true;
        foreach($conditions as $col => $value) {
            if(!isset($row[$col]) || $row[$col] !== $value) {
                $match = false;
                break;
            }
        }

        if($match) {
            unset($rows[$index]);
            $deleted++;
        }
    }

    // Re-index array after deletion
    $rows = array_values($rows);

    // Save updated rows
    $this->storage->saveTable($tableName, $rows);

    // Rebuild indexes
    $this->indexer->saveIndex($tableName, $schema['primary_key'], $this->indexer->buildIndex($rows, $schema['primary_key']));
    if(isset($schema['unique'])) {
        foreach($schema['unique'] as $col) {
            $this->indexer->saveIndex($tableName, $col, $this->indexer->buildIndex($rows, $col));
        }
    }

    echo "$deleted row(s) deleted successfully!\n";
}


}

?>
