<?php
class Index {
    private $indexesPath;

    public function __construct() {
        $this->indexesPath = __DIR__ . '/../data/indexes/';
    }

    // Save an index to disk
    public function saveIndex($tableName, $columnName, $index) {
        $file = $this->indexesPath . $tableName . '_' . $columnName . '.json';
        file_put_contents($file, json_encode($index, JSON_PRETTY_PRINT));
    }

    // Load an index from disk
    public function loadIndex($tableName, $columnName) {
        $file = $this->indexesPath . $tableName . '_' . $columnName . '.json';
        if(!file_exists($file)) {
            return [];
        }
        $data = file_get_contents($file);
        return json_decode($data, true);
    }

    // Build index from table rows
    public function buildIndex($rows, $columnName) {
        $index = [];
        foreach($rows as $i => $row) {
            $index[$row[$columnName]] = $i;
        }
        return $index;
    }
}
?>
