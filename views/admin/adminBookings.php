<?php

require_once "../../models/dbConnect.php";
require_once "../../models/adminBookingModel.php";

$conn          = dbConnection();
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$allowed       = array('pending', 'confirmed', 'rejected', 'cancelled');
$filter_status = in_array($filter_status, $allowed) ? $filter_status : '';
$bookings      = getAllBookings($conn, $filter_status);

$total_bookings     = countAllBookings($conn);
$confirmed_bookings = countBookingsByStatus($conn, 'confirmed');
$pending_bookings   = countBookingsByStatus($conn, 'pending');
$cancelled_bookings = countBookingsByStatus($conn, 'rejected');
mysqli_close($conn);

require_once "header.php";

function bookingBadgeClass($status) {
    if ($status == 'confirmed') return 'active';
    if ($status == 'pending')   return 'pending';
    return 'inactive';
}
?>

<h1 class="page-title">Booking Monitoring</h1>
<p style="color:#666; margin-bottom:20px;">Admin view only &mdash; bookings are created by Customers and accepted/rejected by Turf Owners.</p>

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

<div style="margin-bottom:16px;">
    <a href="adminBookings.php" class="btn btn-sm <?php echo $filter_status == '' ? 'btn-primary' : 'btn-secondary'; ?>">All</a>
    <a href="adminBookings.php?status=pending" class="btn btn-sm <?php echo $filter_status == 'pending' ? 'btn-primary' : 'btn-secondary'; ?>">Pending</a>
    <a href="adminBookings.php?status=confirmed" class="btn btn-sm <?php echo $filter_status == 'confirmed' ? 'btn-primary' : 'btn-secondary'; ?>">Confirmed</a>
    <a href="adminBookings.php?status=rejected" class="btn btn-sm <?php echo $filter_status == 'rejected' ? 'btn-primary' : 'btn-secondary'; ?>">Rejected</a>
</div>

<div class="table-container">
    <div class="flex-between">
        <h3>Bookings (<?php echo count($bookings); ?>)</h3>
    </div>
    <table>
        <thead>
            <tr><th>#</th><th>Customer</th><th>Turf</th><th>Date</th><th>Time</th><th>Price</th><th>Status</th></tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($bookings as $b): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($b['customer_name']); ?></td>
                <td>
                    <?php echo htmlspecialchars($b['turf_name']); ?><br>
                    <span style="font-size:12px; color:#777;"><?php echo htmlspecialchars($b['turf_location']); ?></span>
                </td>
                <td><?php echo date('d M Y', strtotime($b['booking_date'])); ?></td>
                <td><?php echo date('g:i A', strtotime($b['start_time'])) . ' - ' . date('g:i A', strtotime($b['end_time'])); ?></td>
                <td>Tk <?php echo number_format($b['total_price'], 0); ?></td>
                <td><span class="badge badge-<?php echo bookingBadgeClass($b['status']); ?>"><?php echo strtoupper($b['status']); ?></span></td>
            </tr>
            <?php endforeach; ?>
            <?php if (count($bookings) == 0): ?>
            <tr><td colspan="7" style="text-align:center; color:#777; padding:20px;">No bookings found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once "footer.php"; ?>
