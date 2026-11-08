<?php

//Get payload from frontend
$first_name = $_POST['fname'];
$last_name = $_POST['lname'];
$ident_number = $_POST['idnumber'];
$mobile_phone = $_POST['mphone'];
$email = $_POST['email'];
$passwd = MD5($_POST['pswd']);

echo "<br>" . $first_name;
echo "<br>" . $last_name;
echo "<br>" . $ident_number;
echo "<br>" . $mobile_phone;
echo "<br>" . $email;
echo "<br>" . $passwd;

?>