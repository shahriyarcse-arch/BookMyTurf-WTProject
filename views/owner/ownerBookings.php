<?php
session_start();
if (!isset($_SESSION["userId"]) || ($_SESSION["role"] ?? "") != "owner") {
    header("Location: ../login.php");
    exit();
}

require_once "../../models/ownerBookingsModel.php";
$ownerId = $_SESSION["userId"];
$bookings = getBookingsByOwner($ownerId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Bookings - Owner Panel</title>
    <link rel="stylesheet" href="../css/dashboard.css?v=<?php echo time(); ?>">
</head>
<body>

    <div class="dashboard-wrapper">
        
        <aside class="left-sidebar">
            <div class="sidebar-brand">
                <h2>Owner Panel</h2>
            </div>

            <nav class="sidebar-menu">
                <a href="ownerDashboard.php" class="nav-item">Dashboard</a>
                <a href="addTurf.php" class="nav-item">Add Turf</a>
                <a href="myTurfs.php" class="nav-item">My Turfs</a>
                <a href="ownerBookings.php" class="nav-item active">Bookings</a>
            </nav>

            <div class="logout-section">
                <a href="../logout.php" class="btn-logout">Logout</a>
            </div>
        </aside>

        <main class="right-content">
            
            <div class="welcome-box">
                <h1>Customer Bookings</h1>
            </div>

            <?php if(isset($_GET["successMsg"])): ?>
                <div class="alert-box success-alert">
                    <?php echo htmlspecialchars($_GET["successMsg"]); ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET["errorMsg"])): ?>
                <div class="alert-box error-alert">
                    <?php echo htmlspecialchars($_GET["errorMsg"]); ?>
                </div>
            <?php endif; ?>

            <div class="table-container-card">
                
                <?php if(empty($bookings)): ?>
                    <div class="empty-box">
                        <h3>No Bookings Received Yet!</h3>
                        <p>When customers reserve time slots on your turfs, their booking requests will appear here for your confirmation.</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Turf Name</th>
                                <th>Date</th>
                                <th>Time Slot</th>
                                <th>Total Bill</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 1;
                            foreach($bookings as $b): 
                                $statusClass = "status-" . strtolower($b["status"]);
                            ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td>
                                        <b><?php echo htmlspecialchars($b["customer_name"]); ?></b>
                                        <div style="font-size: 20px; color: #666; margin-top: 6px;"><?php echo htmlspecialchars($b["customer_phone"]); ?></div>
                                    </td>
                                    <td><?php echo htmlspecialchars($b["turf_name"]); ?></td>
                                    <td><b><?php echo htmlspecialchars($b["booking_date"]); ?></b></td>
                                    <td><?php echo date("h:i A", strtotime($b["start_time"])) . " - " . date("h:i A", strtotime($b["end_time"])); ?></td>
                                    <td><b style="color: #d97706;"><?php echo number_format($b["total_price"], 2); ?></b></td>
                                    <td>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <?php echo ucfirst($b["status"]); ?>
                                        </span>
                                    </td>
                                    <td class="actions-cell">
                                        <?php if($b["status"] == "pending"): ?>
                                            <a href="../../controllers/bookingControls.php?action=confirm&id=<?php echo $b["id"]; ?>" 
                                               class="action-btn confirm-btn"
                                               onclick="return confirm('Confirm this customer booking?');">
                                               Confirm
                                            </a>
                                            <a href="../../controllers/bookingControls.php?action=reject&id=<?php echo $b["id"]; ?>" 
                                               class="action-btn delete-btn"
                                               onclick="return confirm('Reject this booking request?');">
                                               Reject
                                            </a>
                                        <?php elseif($b["status"] == "confirmed"): ?>
                                            <a href="../../controllers/bookingControls.php?action=reject&id=<?php echo $b["id"]; ?>" 
                                               class="action-btn delete-btn"
                                               onclick="return confirm('Cancel this confirmed booking?');">
                                               Cancel
                                            </a>
                                        <?php else: ?>
                                            <span style="color: #888; font-size: 20px; font-weight: bold;">Completed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

            </div>

        </main>

    </div>

</body>
</html>
