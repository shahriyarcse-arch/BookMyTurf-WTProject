<?php

require_once "../../models/dbConnect.php";
require_once "../../models/adminUserModel.php";
require_once "../../models/adminTurfModel.php";
require_once "../../models/adminBookingModel.php";

$conn = dbConnection();

$total_customers = countUsersByRole($conn, 'customer');
$total_owners    = countUsersByRole($conn, 'owner');

$total_turfs   = countAllTurfs($conn);
$pending_turfs = countTurfsByStatus($conn, 'pending');

$total_bookings     = countAllBookings($conn);
$confirmed_bookings = countBookingsByStatus($conn, 'confirmed');
$pending_bookings   = countBookingsByStatus($conn, 'pending');
$cancelled_bookings = countBookingsByStatus($conn, 'rejected');

$sql_recent   = "SELECT id, name, email, role, created_at FROM users ORDER BY id DESC LIMIT 5";
$result       = mysqli_query($conn, $sql_recent);
$recent_users = array();
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $recent_users[] = $row;
    }
}

$pending_list = getAllTurfs($conn, 'pending');
$pending_list = array_slice($pending_list, 0, 5);

mysqli_close($conn);
require_once "header.php";
?>

<h1 class="page-title">Admin Dashboard</h1>
<p style="color:#666; margin-bottom:20px;">Welcome, <strong><?php echo htmlspecialchars($adminDisplayName); ?></strong> &mdash; Full Platform Overview</p>

<div class="card-row">
    <div class="card">
        <h3>Customers</h3>
        <div class="stat-number"><?php echo $total_customers; ?></div>
    </div>
    <div class="card orange">
        <h3>Turf Owners</h3>
        <div class="stat-number"><?php echo $total_owners; ?></div>
    </div>
    <div class="card green">
        <h3>Total Turfs</h3>
        <div class="stat-number"><?php echo $total_turfs; ?></div>
    </div>
    <div class="card purple">
        <h3>Pending Turf Approvals</h3>
        <div class="stat-number"><?php echo $pending_turfs; ?></div>
    </div>
</div>

<div class="card-row">
    <div class="card">
        <h3>Total Bookings</h3>
        <div class="stat-number"><?php echo $total_bookings; ?></div>
    </div>
    <div class="card green">
        <h3>Confirmed</h3>
        <div class="stat-number"><?php echo $confirmed_bookings; ?></div>
    </div>
    <div class="card orange">
        <h3>Pending</h3>
        <div class="stat-number"><?php echo $pending_bookings; ?></div>
    </div>
    <div class="card red">
        <h3>Cancelled / Rejected</h3>
        <div class="stat-number"><?php echo $cancelled_bookings; ?></div>
    </div>
</div>

<div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:28px;">
    <a href="manageUsers.php" class="btn btn-primary">Manage Users</a>
    <a href="manageTurfs.php" class="btn btn-success">Approve / Manage Turfs</a>
    <a href="adminBookings.php" class="btn btn-secondary">Monitor Bookings</a>
</div>

<div class="section-box">
    <div class="flex-between">
        <h3>Turfs Awaiting Approval</h3>
        <a href="manageTurfs.php" class="btn btn-primary btn-sm">View All</a>
    </div>
    <?php if (count($pending_list) == 0): ?>
        <p style="color:#777; padding:10px 0;">No turfs are waiting for approval.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr><th>#</th><th>Turf</th><th>Owner</th><th>Location</th><th>Price/Hour</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($pending_list as $t): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><strong><?php echo htmlspecialchars($t['name']); ?></strong></td>
                <td><?php echo htmlspecialchars($t['owner_name']); ?></td>
                <td><?php echo htmlspecialchars($t['location']); ?></td>
                <td>Tk <?php echo number_format($t['price_per_hour'], 0); ?></td>
                <td>
                    <a href="../../controllers/adminTurfControls.php?action=approve&id=<?php echo $t['id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve this turf?')">Approve</a>
                    <a href="../../controllers/adminTurfControls.php?action=reject&id=<?php echo $t['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Reject this turf?')">Reject</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<div class="section-box">
    <div class="flex-between">
        <h3>Recently Registered Users</h3>
        <a href="manageUsers.php" class="btn btn-primary btn-sm">View All</a>
    </div>
    <table>
        <thead>
            <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th></tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($recent_users as $u): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($u['name']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><span class="badge badge-active"><?php echo strtoupper($u['role']); ?></span></td>
                <td>
                    <span class="badge badge-<?php echo ($u['status'] ?? 'active') == 'active' ? 'active' : 'inactive'; ?>">
                        <?php echo strtoupper($u['status'] ?? 'active'); ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once "footer.php"; ?>
