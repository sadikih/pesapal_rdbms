<?php
require_once __DIR__ . '/rdbms/core/Storage.php';



$storage = new Storage();

// Test saving a table
$storage->saveTable('users', [
    ['id' => 1, 'name' => 'Alice'],
    ['id' => 2, 'name' => 'Bob']
]);

// Test loading the table
$users = $storage->loadTable('users');
print_r($users);
?>
