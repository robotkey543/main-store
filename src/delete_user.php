<?php
    /*
    Use this Endpoint to test request
    http://localhost/main-store/src/delete_user.php?userID=ANY-ID-NUMBER
    */
    include('settings/database.php');

    // Get data
    $user_id = $_GET['userID'];

    // Prepare query
    $sql_delete_user = "DELETE FROM users WHERE id = $user_id ";
    $res = pg_query($local_conn, $sql_delete_user);

    if($res){
    echo "<script>alert('User has been deleted successfully !!!')</script>";
    header('refresh:0;url=list_user.php');
}else{
    echo "<script>alert('User has not been deleted !!!')</script>";
    header('refresh:0;url=list_user.php');
}