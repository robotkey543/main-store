<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.html');
    exit();
}

/*
Use this URL to print data in browser:
http://localhost/main-store/src/list_user.php
http://localhost/main-store/src/list_user.php?filter=active
http://localhost/main-store/src/list_user.php?filter=inactive
*/
include('settings/database.php');

// Filter: all | active | inactive
$filter = $_GET['filter'] ?? 'all';
if ($filter === 'active') {
    $where = 'where status = true';
} elseif ($filter === 'inactive') {
    $where = 'where status = false';
} else {
    $filter = 'all';
    $where = '';
}

//Get data from users table
$sql = "
select
    id,
    firstname || ' ' || lastname as fullname,
    email,
    mobile_phone,
    case when status = true then 'Active' else 'Inactive' end as status,
    url_photo
from
    users
$where
order by id
";
$res = pg_query($local_conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Store - List Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <select onchange="window.location.href='list_user.php?filter=' + this.value">
        <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>List all users</option>
        <option value="active" <?= $filter === 'active' ? 'selected' : '' ?>>List active users only</option>
        <option value="inactive" <?= $filter === 'inactive' ? 'selected' : '' ?>>List inactive users only</option>
    </select>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Fullname</th>
                <th>Email</th>
                <th>Mobile phone</th>
                <th>Status</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = pg_fetch_assoc($res)) { ?>
            <tr>
                <td>
                    <?php if (!empty($row['url_photo'])) { ?>
                        <img src="<?= $row['url_photo'] ?>" width="40" alt="User photo profile">
                    <?php } else { ?>
                        Sin foto
                    <?php } ?>
                </td>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['mobile_phone']) ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <!-- Edit -->
                    <a href="edit_user.php?id=<?= $row['id'] ?>">
                        <img src="icons/edit.png" alt="edit user" width="20">
                    </a>

                    <!-- Search -->
                    <a href="#">
                        <img src="icons/search.png" alt="search user" width="20">
                    </a>

                    <!-- Enable / Disable -->
                    <?php if ($row['status'] === 'Active') { ?>
                        <a href="update_user_status.php?userID=<?= $row['id'] ?>&action=inactivate&filter=<?= $filter ?>"
                           onclick="return confirm('¿Estás seguro de que quieres desactivar este usuario?');">
                            <img src="icons/enable.png" alt="user active, click to disable" width="20">
                        </a>
                    <?php } else { ?>
                        <a href="update_user_status.php?userID=<?= $row['id'] ?>&action=activate&filter=<?= $filter ?>">
                            <img src="icons/disable.png" alt="user inactive, click to enable" width="20">
                        </a>
                    <?php } ?>

                    <!-- Delete -->
                    <a href="delete_user.php?userID=<?= $row['id'] ?>&action=delete"
                       onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?');">
                        <img src="icons/delete.png" alt="delete user" width="20">
                    </a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <a href="home.php">Go to home</a>
</body>
</html>