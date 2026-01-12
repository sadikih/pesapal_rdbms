<?php
require_once __DIR__ . '/rdbms/core/Executor.php';

$executor = new Executor();

// Update Bob's 'active' status to true
$executor->update('users', ['name' => 'Bob'], ['active' => true]);

// Verify update
$allRows = $executor->read('users');
print_r($allRows);
?>
