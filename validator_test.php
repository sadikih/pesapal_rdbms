<?php
require_once __DIR__ . '/rdbms/core/Validator.php';


$validator = new Validator();

$schema = [
    'columns' => [
        'id' => ['type' => 'INTEGER'],
        'name' => ['type' => 'STRING'],
        'active' => ['type' => 'BOOLEAN']
    ],
    'primary_key' => 'id',
    'unique' => ['name']
];

$tableRows = [
    ['id' => 1, 'name' => 'Alice', 'active' => true]
];

// Test a valid row
$newRow = ['id' => 2, 'name' => 'Bob', 'active' => false];
$validator->validateRow($newRow, $schema);
$validator->validatePrimaryKey($newRow, $tableRows, $schema['primary_key']);
$validator->validateUnique($newRow, $tableRows, $schema['unique']);

echo "Row is valid!";
?>
