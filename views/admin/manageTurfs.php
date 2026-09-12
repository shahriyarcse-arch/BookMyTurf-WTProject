<?php

require_once "../../models/dbConnect.php";
require_once "../../models/adminTurfModel.php";

$conn          = dbConnection();
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$allowed       = array('pending', 'approved', 'active', 'rejected', 'inactive');
$filter_status = in_array($filter_status, $allowed) ? $filter_status : '';
$turfs         = getAllTurfs($conn, $filter_status);
mysqli_close($conn);

require_once "header.php";

function statusBadgeClass($status) {
    if ($status == 'approved' || $status == 'active') return 'active';
    if ($status == 'pending')  return 'pending';
    return 'inactive';
}
?>

<h1 class="page-title">Turf Approval &amp; Management</h1>

<div style="margin-bottom:16px;">
    <a href="manageTurfs.php" class="btn btn-sm <?php echo $filter_status == '' ? 'btn-primary' : 'btn-secondary'; ?>">All</a>
    <a href="manageTurfs.php?status=pending" class="btn btn-sm <?php echo $filter_status == 'pending' ? 'btn-primary' : 'btn-secondary'; ?>">Pending</a>
    <a href="manageTurfs.php?status=approved" class="btn btn-sm <?php echo ($filter_status == 'approved' || $filter_status == 'active') ? 'btn-primary' : 'btn-secondary'; ?>">Approved</a>
    <a href="manageTurfs.php?status=rejected" class="btn btn-sm <?php echo $filter_status == 'rejected' ? 'btn-primary' : 'btn-secondary'; ?>">Rejected</a>
    <a href="manageTurfs.php?status=inactive" class="btn btn-sm <?php echo $filter_status == 'inactive' ? 'btn-primary' : 'btn-secondary'; ?>">Inactive</a>
</div>

<div class="table-container">
    <div class="flex-between">
        <h3>Turfs (<?php echo count($turfs); ?>)</h3>
    </div>
    <table>
        <thead>
            <tr><th>#</th><th>Turf</th><th>Owner</th><th>Location</th><th>Price/Hour</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($turfs as $t): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><strong><?php echo htmlspecialchars($t['name']); ?></strong></td>
                <td>
                    <?php echo htmlspecialchars($t['owner_name']); ?><br>
                    <span style="font-size:12px; color:#777;"><?php echo htmlspecialchars($t['owner_email']); ?></span>
                </td>
                <td><?php echo htmlspecialchars($t['location']); ?></td>
                <td>Tk <?php echo number_format($t['price_per_hour'], 0); ?></td>
                <td><span class="badge badge-<?php echo statusBadgeClass($t['status']); ?>"><?php echo strtoupper($t['status']); ?></span></td>
                <td>
                    <?php if ($t['status'] == 'pending'): ?>
                        <a href="../../controllers/adminTurfControls.php?action=approve&id=<?php echo $t['id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve this turf?')">Approve</a>
                        <a href="../../controllers/adminTurfControls.php?action=reject&id=<?php echo $t['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Reject this turf?')">Reject</a>
                    <?php elseif ($t['status'] == 'approved' || $t['status'] == 'active'): ?>
                        <a href="../../controllers/adminTurfControls.php?action=deactivate&id=<?php echo $t['id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Deactivate this turf?')">Deactivate</a>
                    <?php elseif ($t['status'] == 'inactive'): ?>
                        <a href="../../controllers/adminTurfControls.php?action=activate&id=<?php echo $t['id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Re-activate this turf?')">Activate</a>
                    <?php elseif ($t['status'] == 'rejected'): ?>
                        <a href="../../controllers/adminTurfControls.php?action=approve&id=<?php echo $t['id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve this turf?')">Approve</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (count($turfs) == 0): ?>
            <tr><td colspan="7" style="text-align:center; color:#777; padding:20px;">No turfs found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once "footer.php"; ?>
