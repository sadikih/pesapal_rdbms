<?php
require_once __DIR__ . '/rdbms/core/Executor.php';
$executor = new Executor();

// Handle form submissions
$message = '';
if(isset($_POST['action'])) {
    $action = $_POST['action'];
    if($action === 'create') {
        $executor->create('users', [
            'id' => (int)$_POST['id'],
            'name' => $_POST['name'],
            'active' => isset($_POST['active']) ? true : false
        ]);
        $message = 'User created successfully!';
    } elseif($action === 'update') {
        $executor->update('users', ['id' => (int)$_POST['id']], [
            'name' => $_POST['name'],
            'active' => isset($_POST['active']) ? true : false
        ]);
        $message = 'User updated successfully!';
    } elseif($action === 'delete') {
        $executor->delete('users', ['id' => (int)$_POST['id']]);
        $message = 'User deleted successfully!';
    }
}

// Fetch all users
$users = $executor->read('users');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pesapal RDBMS Demo</title>
</head>
<body>
    <h1>Pesapal RDBMS Demo</h1>

    <?php if($message) echo "<p><b>$message</b></p>"; ?>

    <h2>Create / Update User</h2>
    <form method="post">
        <input type="hidden" name="action" value="create">
        ID: <input type="number" name="id" required>
        Name: <input type="text" name="name" required>
        Active: <input type="checkbox" name="active">
        <button type="submit">Create</button>
    </form>

    <h2>Update User</h2>
    <form method="post">
        <input type="hidden" name="action" value="update">
        ID (to update): <input type="number" name="id" required>
        New Name: <input type="text" name="name" required>
        Active: <input type="checkbox" name="active">
        <button type="submit">Update</button>
    </form>

    <h2>Delete User</h2>
    <form method="post">
        <input type="hidden" name="action" value="delete">
        ID: <input type="number" name="id" required>
        <button type="submit">Delete</button>
    </form>

    <h2>All Users</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Active</th>
        </tr>
        <?php foreach($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['active'] ? 'Yes' : 'No' ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
