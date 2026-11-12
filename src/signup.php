<?php
//Get datavase connection
include('settings/database.php');

//Get payload from frontend
$first_name   = $_POST['fname'];
$last_name    = $_POST['lname'];
$ident_number = $_POST['idnumber'];
$mobile_phone = $_POST['mphone'];
$email        = $_POST['email'];
$passwd       = MD5($_POST['psw']);

$sql_check_email = "SELECT * FROM users WHERE email = $1 ";
$params = [$email];
$res = pg_query_params($local_conn, $sql_check_email, $params);
$total = pg_num_rows($res);
if($total < 1){
//Prepare query
$sql = "
    INSERT INTO users (firstname, lastname, ident_number, mobile_phone, email, password)
    VALUES ($1, $2, $3, $4, $5, $6)
";
$params = [$first_name, $last_name, $ident_number, $mobile_phone, $email, $passwd];

//Execute query in both databases
$res_local = pg_query_params($local_conn, $sql, $params);
$res_supa  = pg_query_params($supa_conn, $sql, $params);

if ($res_local && $res_supa) {
    //echo "User has been created successfully in local and supa !!!";
    echo "<script>alert('User has been created successfully!!!')</script>";
    header('refresh:0;url=signin.html');
} else {
    if (!$res_local) echo "Error in LOCAL: " . pg_last_error($local_conn) . "<br>";
    if (!$res_supa)  echo "Error in SUPA: "  . pg_last_error($supa_conn)  . "<br>";
}
} else{
   echo "<script>alert('User email already exists. Try again !!!')</script>";
    header('refresh:0;url=signup.html'); 
}   
?>