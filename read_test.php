<?php
require_once __DIR__ . '/rdbms/core/Executor.php';

$executor = new Executor();

// Read all rows
$allRows = $executor->read('users');
echo "All users:\n";
print_r($allRows);

// Read filtered rows
$filtered = $executor->read('users', ['name' => 'Alice']);
echo "Filtered users (name = Alice):\n";
print_r($filtered);
?>
