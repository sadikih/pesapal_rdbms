<?php
require_once __DIR__ . '/rdbms/core/Executor.php';
require_once __DIR__ . '/rdbms/core/Storage.php';

// Initialize storage to create schema
$storage = new Storage();

// Create a table schema for testing
$schema = [
    'columns' => [
        'id' => ['type' => 'INTEGER'],
        'name' => ['type' => 'STRING'],
        'active' => ['type' => 'BOOLEAN']
    ],
    'primary_key' => 'id',
    'unique' => ['name']
];
$storage->saveSchema('users', $schema);

$executor = new Executor();

// Add first row
$executor->create('users', ['id' => 101, 'name' => 'Alice', 'active' => true]);

// Add second row
$executor->create('users', ['id' => 102, 'name' => 'Bob', 'active' => false]);
?>
