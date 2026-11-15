<?php
/*
Use this URL to print data in browser:
http://localhost/main-store/src/list_user.php
*/
include('settings/database.php');

//Get data from users table
$sql="
select
    id,
    firstname || ' ' || lastname as fullname,
    email,
    mobile_phone,
    case when status = true then 'Active' else 'Inactive' end as status,
    url_photo
from
    users
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
</head>

<body>
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
                <!-- Here print data from database -->
                <td>
                    <?php if (!empty($row['url_photo'])) { ?>
                        <img src="<?= $row['url_photo'] ?>" width="40" alt="User photo profile">
                    <?php } else { ?>
                        Sin foto
                    <?php } ?>
                </td>
                <td><?= $row['fullname'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['mobile_phone'] ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $row['id'] ?>">
                        <img src="icons/edit.png" alt="edit user" width="20">
                    </a>
                    <a href="#">
                        <img src="icons/search.png" alt="search user" width="20">
                    </a>
                    <a href="#" onclick="askDelete(<?= $row['id'] ?>); return false;">
                        <img src="icons/delete.png" alt="delete user" width="20">
                    </a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <!-- Delete / Inactivate modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete user</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    What do you want to do with this user?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" onclick="confirmAction('inactivate')">Inactivate</button>
                    <button type="button" class="btn btn-danger" onclick="confirmAction('delete')">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let selectedUserId = null;

    function askDelete(id) {
        selectedUserId = id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    function confirmAction(action) {
        let text = (action === 'delete')
            ? 'Are you sure you want to DELETE this user permanently?'
            : 'Are you sure you want to INACTIVATE this user?';
        if (confirm(text)) {
            window.location.href = 'delete_user.php?userID=' + selectedUserId + '&action=' + action;
        }
    }
    </script>
</body>
</html>