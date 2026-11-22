<?php
//Get database connection
include('settings/database.php');

session_start();

//Get payload from frontend
$email  = $_POST['email'] ?? '';
$passwd = $_POST['psw'] ?? '';

$sql = "
    SELECT id, firstname, lastname, email, url_photo, password, status
    FROM users
    WHERE email = $1";
$res = pg_query_params($local_conn, $sql, [$email]);
$row = pg_fetch_assoc($res);

$msg_error = "Invalid email or password. Try again !!!";

// Email no existe
if (!$row) {
    echo "<script>alert('$msg_error'); window.location='signin.html';</script>";
    exit;
}

// Usuario inactivo
if ($row['status'] === 'f') {
    echo "<script>alert('User is inactive. Contact the administrator.'); window.location='signin.html';</script>";
    exit;
}

// Verificar contraseña con hash
$valid = password_verify($passwd, $row['password']);

// Usuarios viejos con MD5: se aceptan y se convierten a hash
if (!$valid && hash_equals($row['password'], md5($passwd))) {
    $valid = true;
    pg_query_params(
        $local_conn,
        'UPDATE users SET password = $1 WHERE id = $2',
        [password_hash($passwd, PASSWORD_DEFAULT), $row['id']]
    );
}

if ($valid) {
    session_regenerate_id(true);
    $_SESSION['user_id']        = $row['id'];
    $_SESSION['user_firstname'] = $row['firstname'];
    $_SESSION['user_lastname']  = $row['lastname'];
    header('Location: home.php');
    exit;
}

echo "<script>alert('$msg_error'); window.location='signin.html';</script>";
?>