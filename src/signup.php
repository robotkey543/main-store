<?php
include('settings/database.php');

// Get payload from frontend
$first_name    = $_POST['fname'] ?? '';
$last_name     = $_POST['lname'] ?? '';
$ident_number  = $_POST['idnumber'] ?? '';
$mobile_phone  = $_POST['mphone'] ?? '';
$email         = $_POST['email'] ?? '';
$passwd        = password_hash($_POST['psw'] ?? '', PASSWORD_DEFAULT);
$PHOTO_URL     = 'photos/photo_default.png';

$sql_check_email = "SELECT 1 FROM users WHERE email = $1";
$res   = pg_query_params($local_conn, $sql_check_email, [$email]);
$total = pg_num_rows($res);

if ($total < 1) {
    $sql = "
        INSERT INTO users (firstname, lastname, ident_number, mobile_phone, email, password, url_photo)
        VALUES ($1, $2, $3, $4, $5, $6, $7)
    ";
    $params = [$first_name, $last_name, $ident_number, $mobile_phone, $email, $passwd, $PHOTO_URL];

    // Execute query in both databases
    $res_local = pg_query_params($local_conn, $sql, $params);
    $res_supa  = pg_query_params($supa_conn, $sql, $params);

    if ($res_local && $res_supa) {
        echo "<script>alert('User has been created successfully!!!'); window.location='signin.html';</script>";
    } else {
        if (!$res_local) error_log("Error LOCAL: " . pg_last_error($local_conn));
        if (!$res_supa)  error_log("Error SUPA: "  . pg_last_error($supa_conn));
        echo "<script>alert('Could not create the user. Try again later.'); window.location='signup.html';</script>";
    }
} else {
    echo "<script>alert('User email already exists. Try again !!!'); window.location='signup.html';</script>";
}
?>