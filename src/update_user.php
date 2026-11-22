<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.html');
    exit();
}

include('settings/database.php');

$id           = $_POST['id'];
$firstname    = $_POST['firstname'];
$lastname     = $_POST['lastname'];
$email        = $_POST['email'];
$mobile_phone = $_POST['mobile_phone'];

// Parte A: actualizar los datos
$sql = 'UPDATE users
        SET firstname = $1, lastname = $2, email = $3, mobile_phone = $4, updated_at = NOW()
        WHERE id = $5';
$result = pg_query_params($local_conn, $sql, array($firstname, $lastname, $email, $mobile_phone, $id));

if (!$result) {
    echo "Error al actualizar los datos";
    exit;
}

// Parte B: la foto (solo si eligieron una)
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0 && $_FILES['photo']['name'] != "") {

    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

    if ($ext == "jpg" || $ext == "jpeg" || $ext == "png") {

        // nombre de la foto = celular (solo números) + extensión
        $clean_phone = preg_replace('/\D/', '', $mobile_phone);
        $photo_url = "photos/" . $clean_phone . "." . $ext;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photo_url)) {
            $sql = 'UPDATE users SET url_photo = $1 WHERE id = $2';
            pg_query_params($local_conn, $sql, array($photo_url, $id));
        } else {
            echo "No se pudo guardar la foto. Revisa la carpeta photos.";
            exit;
        }

    } else {
        echo "Solo se permiten fotos JPG o PNG. <a href='edit_user.php?id=$id'>Volver</a>";
        exit;
    }
}

// Parte C: volver a la lista
header("Location: list_user.php");
exit;
?>