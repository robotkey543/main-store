<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.html');
    exit();
}

include('settings/database.php');

$id = $_GET['id'];

$sql = 'SELECT * FROM users WHERE id = $1';
$result = pg_query_params($local_conn, $sql, array($id));
$user = pg_fetch_assoc($result);

if (!$user) {
    echo "Usuario no encontrado. <a href='list_user.php'>Volver</a>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Store - Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h3>Edit user</h3>

    <form action="update_user.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <label>Firstname</label><br>
        <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required><br><br>

        <label>Lastname</label><br>
        <input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required><br><br>

        <label>E-mail</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

        <label>Mobile phone</label><br>
        <input type="text" name="mobile_phone" value="<?= htmlspecialchars($user['mobile_phone']) ?>" required><br><br>

        <?php if (!empty($user['url_photo'])) { ?>
            <p>Current photo:</p>
            <img src="<?= $user['url_photo'] ?>" width="120"><br><br>
        <?php } ?>

        <label>Photo (JPG or PNG)</label><br>
        <input type="file" name="photo" accept=".jpg,.jpeg,.png"><br><br>

        <button type="submit">Save</button>
        <a href="list_user.php">Cancel</a>
    </form>
</body>
</html>