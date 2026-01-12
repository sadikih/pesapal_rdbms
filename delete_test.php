<?php
require_once __DIR__ . '/rdbms/core/Executor.php';

$executor = new Executor();

// Delete Alice from users table
$executor->delete('users', ['name' => 'Alice']);

// Verify deletion
$allRows = $executor->read('users');
print_r($allRows);
?>
