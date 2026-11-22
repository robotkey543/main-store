<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.html');
    exit();
}

/*
Use this Endpoint to test request
http://localhost/main-store/src/delete_user.php?userID=ANY-ID-NUMBER&action=delete
http://localhost/main-store/src/delete_user.php?userID=ANY-ID-NUMBER&action=inactivate
http://localhost/main-store/src/delete_user.php?userID=ANY-ID-NUMBER&action=activate
*/
include('settings/database.php');

// Get data
$user_id = $_GET['userID'] ?? '';
$action  = $_GET['action'] ?? '';

if ($user_id === '') {
    header('refresh:0;url=list_user.php');
    exit;
}
$action  = $_GET['action'] ?? '';

// Prepare query
if ($action === 'delete') {
    $sql = 'DELETE FROM users WHERE id = $1';
    $msg = 'User has been deleted successfully !!!';
} elseif ($action === 'inactivate') {
    $sql = 'UPDATE users SET status = false, deleted_at = NOW() WHERE id = $1';
    $msg = 'User has been inactivated successfully !!!';
} elseif ($action === 'activate') {
    $sql = 'UPDATE users SET status = true, deleted_at = NULL WHERE id = $1';
    $msg = 'User has been activated successfully !!!';
} else {
    echo "<script>alert('Invalid action !!!')</script>";
    header('refresh:0;url=list_user.php');
    exit;
}

$res = pg_query_params($local_conn, $sql, array($user_id));

if ($res) {
    echo "<script>alert('$msg')</script>";
} else {
    echo "<script>alert('Action could not be completed !!!')</script>";
}
header('refresh:0;url=list_user.php');
?>