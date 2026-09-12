<?php

require_once "../../models/dbConnect.php";
require_once "../../models/adminUserModel.php";

$conn         = dbConnection();
$filter_role  = isset($_GET['role']) ? $_GET['role'] : '';
$allowed_role = in_array($filter_role, array('customer', 'owner')) ? $filter_role : '';
$users        = getAllUsers($conn, $allowed_role);
mysqli_close($conn);

require_once "header.php";
?>

<h1 class="page-title">Manage Users</h1>

<div style="margin-bottom:16px;">
    <a href="manageUsers.php" class="btn btn-sm <?php echo $allowed_role == '' ? 'btn-primary' : 'btn-secondary'; ?>">All</a>
    <a href="manageUsers.php?role=customer" class="btn btn-sm <?php echo $allowed_role == 'customer' ? 'btn-primary' : 'btn-secondary'; ?>">Customers</a>
    <a href="manageUsers.php?role=owner" class="btn btn-sm <?php echo $allowed_role == 'owner' ? 'btn-primary' : 'btn-secondary'; ?>">Turf Owners</a>
</div>

<div class="table-container">
    <div class="flex-between">
        <h3>Users (<?php echo count($users); ?>)</h3>
    </div>
    <table>
        <thead>
            <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Phone</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($users as $u): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><strong><?php echo htmlspecialchars($u['name']); ?></strong></td>
                <td style="font-size:13px;"><?php echo htmlspecialchars($u['email']); ?></td>
                <td><span class="badge badge-active"><?php echo strtoupper($u['role']); ?></span></td>
                <td style="font-size:13px;"><?php echo htmlspecialchars($u['phone'] ?? '-'); ?></td>
                <td>
                    <span class="badge badge-<?php echo ($u['status'] ?? 'active') == 'active' ? 'active' : 'inactive'; ?>">
                        <?php echo strtoupper($u['status'] ?? 'active'); ?>
                    </span>
                </td>
                <td>
                    <?php if ($u['role'] != 'admin'): ?>
                        <a href="../../controllers/adminUserControls.php?action=toggle_status&id=<?php echo $u['id']; ?>"
                           class="btn btn-sm <?php echo ($u['status'] ?? 'active') == 'active' ? 'btn-warning' : 'btn-success'; ?>"
                           onclick="return confirm('<?php echo ($u['status'] ?? 'active') == 'active' ? 'Block' : 'Unblock'; ?> this user?')">
                            <?php echo ($u['status'] ?? 'active') == 'active' ? 'Block' : 'Unblock'; ?>
                        </a>
                    <?php else: ?>
                        <span style="color:#999; font-size:12px;">Admin</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (count($users) == 0): ?>
            <tr><td colspan="7" style="text-align:center; color:#777; padding:20px;">No users found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once "footer.php"; ?>
