<?php
/*
Use this Endpoint to test request
http://localhost/main-store/src/update_user_status.php?userID=ANY-ID-NUMBER&action=inactivate
http://localhost/main-store/src/update_user_status.php?userID=ANY-ID-NUMBER&action=activate
*/
include('settings/database.php');

// Get data
$user_id = $_GET['userID'] ?? '';
$action  = $_GET['action'] ?? '';
$filter  = $_GET['filter'] ?? 'all';

// Prepare query
if ($action === 'inactivate') {
    $sql = 'UPDATE users SET status = false, deleted_at = NOW() WHERE id = $1';
    $msg = 'User has been inactivated successfully !!!';
} elseif ($action === 'activate') {
    $sql = 'UPDATE users SET status = true, deleted_at = NULL WHERE id = $1';
    $msg = 'User has been activated successfully !!!';
} else {
    echo "<script>alert('Invalid action !!!')</script>";
    header('refresh:0;url=list_user.php?filter=' . urlencode($filter));
    exit;
}

$res = pg_query_params($local_conn, $sql, array($user_id));

if ($res) {
    echo "<script>alert('$msg')</script>";
} else {
    echo "<script>alert('Action could not be completed !!!')</script>";
}
header('refresh:0;url=list_user.php?filter=' . urlencode($filter));
?>