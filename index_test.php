<?php
require_once __DIR__ . '/rdbms/core/Index.php';

$indexer = new Index();

$rows = [
    ['id' => 1, 'name' => 'Alice'],
    ['id' => 2, 'name' => 'Bob']
];

// Build index on id
$idIndex = $indexer->buildIndex($rows, 'id');
$indexer->saveIndex('users', 'id', $idIndex);

// Load the index
$loadedIndex = $indexer->loadIndex('users', 'id');
print_r($loadedIndex);
?>
