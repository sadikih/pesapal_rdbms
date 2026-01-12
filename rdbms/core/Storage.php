<?php
class Storage {
    private $tablesPath;
    private $schemasPath;
    private $indexesPath;

    public function __construct() {
        $this->tablesPath = __DIR__ . '/../data/tables/';
        $this->schemasPath = __DIR__ . '/../data/schemas/';
        $this->indexesPath = __DIR__ . '/../data/indexes/';
    }

    // Save a table as JSON
    public function saveTable($tableName, $rows) {
        $file = $this->tablesPath . $tableName . '.json';
        file_put_contents($file, json_encode($rows, JSON_PRETTY_PRINT));
    }

    // Load a table from JSON
    public function loadTable($tableName) {
        $file = $this->tablesPath . $tableName . '.json';
        if(!file_exists($file)) {
            return [];
        }
        $data = file_get_contents($file);
        return json_decode($data, true);
    }

    // Save a schema
    public function saveSchema($tableName, $schema) {
        $file = $this->schemasPath . $tableName . '.json';
        file_put_contents($file, json_encode($schema, JSON_PRETTY_PRINT));
    }

    // Load a schema
    public function loadSchema($tableName) {
        $file = $this->schemasPath . $tableName . '.json';
        if(!file_exists($file)) {
            return null;
        }
        $data = file_get_contents($file);
        return json_decode($data, true);
    }
}
?>
