<?php
//Get datavase connection
include('settings/database.php');

session_start();
if(!isset($_SESSION['user_id'])){
    header('refresh:0;url=signin.html');
}else{
    header('refresh:0;url=home.php');
}

//Get payload from frontend
$email   = $_POST['email'];
$passwd  = MD5($_POST['psw']);

$sql_check_email = "
    SELECT 
        id,
        firstname,
        lastname,
        email,
        url_photo
    FROM 
        users 
    WHERE 
        email = $1";
$params_email = [$email];
$res_email = pg_query_params($local_conn, $sql_check_email, $params_email);
$total_email = pg_num_rows($res_email);

if ($total_email > 0) {
    $sql_check_pass = "SELECT * FROM users WHERE email = $1 AND password = $2";
    $params_pass = [$email, $passwd];
    $res_pass = pg_query_params($local_conn, $sql_check_pass, $params_pass);
    $total_pass = pg_num_rows($res_pass);
    $row = pg_fetch_assoc($res);

    if ($total_pass > 0) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_firstname'] = $row['firstname'];
        $_SESSION['user_lastname'] = $row['lastname'];
        header('refresh:0;url=home.php');
    } else {
        echo "<script>alert('Incorrect password. Try again !!!')</script>";
        header('refresh:0;url=signin.html');
    }
} else {
    echo "<script>alert('User email does not exist. Try again !!!')</script>";
    header('refresh:0;url=signin.html');
}
?>