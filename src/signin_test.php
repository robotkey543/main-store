<?php
include('settings/database.php');

$email       = $_POST['email'] ?? '';
$passwd_form = $_POST['psw'] ?? '';

$res = pg_query_params($local_conn, 'SELECT password FROM users WHERE email = $1', [$email]);
$row = pg_fetch_assoc($res);

echo "Q1 (email existe): " . ($row ? 'sí' : 'no') . "<br>";

if ($row && password_verify($passwd_form, $row['password'])) {
    echo "Q2 (password correcto): sí<br>You got it.";
} else {
    echo "Q2 (password correcto): no<br>No access.";
}
?>