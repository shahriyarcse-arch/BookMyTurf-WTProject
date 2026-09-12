<?php
require_once "../../models/dbConnect.php";
require_once "../../models/adminUserModel.php";
require_once "header.php";

$conn = dbConnection();
$currentAdminId = $_SESSION['userId'] ?? $_SESSION['user'] ?? 0;
$user = getUserById($conn, $currentAdminId);
mysqli_close($conn);
?>

<h1 class="page-title">My Profile</h1>

<div style="display:flex; gap:20px; flex-wrap:wrap;">

    <div class="section-box" style="flex:1; min-width:300px;">
        <h3>Account Info</h3>
        <form action="../../controllers/adminProfileControls.php" method="POST">
            <input type="hidden" name="action" value="update_info">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Role</label>
                <input type="text" value="<?php echo strtoupper($user['role'] ?? 'ADMIN'); ?>" disabled>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <div class="section-box" style="flex:1; min-width:300px;">
        <h3>Change Password</h3>
        <form action="../../controllers/adminProfileControls.php" method="POST">
            <input type="hidden" name="action" value="change_password">
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" required>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" placeholder="Min 4 characters" required>
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-warning">Change Password</button>
        </form>
    </div>

</div>

<?php require_once "footer.php"; ?>
