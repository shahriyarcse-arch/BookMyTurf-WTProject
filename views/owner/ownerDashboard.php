<?php
session_start();

if (isset($_SESSION["userId"]) && isset($_SESSION["role"])) {
    if ($_SESSION["role"] != "owner") {
        header("Location: ../login.php");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}

require_once "../../models/ownerBookingsModel.php";
$ownerId = $_SESSION["userId"];

$stats = getOwnerDashboardStats($ownerId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Panel - Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css?v=<?php echo time(); ?>">
</head>
<body>

    <div class="dashboard-wrapper">
        
        <aside class="left-sidebar">
            <div class="sidebar-brand">
                <h2>Owner Panel</h2>
            </div>

            <nav class="sidebar-menu">
                <a href="ownerDashboard.php" class="nav-item active">Dashboard</a>
                <a href="addTurf.php" class="nav-item">Add Turf</a>
                <a href="myTurfs.php" class="nav-item">My Turfs</a>
                <a href="ownerBookings.php" class="nav-item">Bookings</a>
            </nav>

            <div class="logout-section">
                <a href="../logout.php" class="btn-logout">Logout</a>
            </div>
        </aside>

        <main class="right-content">
            
            <div class="welcome-box">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION["name"] ?? "Turf Owner"); ?>!</h1>
            </div>

            <div class="stats-row">
                
                <div class="stat-card">
                    <h3>TOTAL TURFS</h3>
                    <div class="number"><?php echo $stats["total_turfs"]; ?></div>
                    <small>Turfs listed by you</small>
                </div>

                <div class="stat-card">
                    <h3>TODAY'S BOOKINGS</h3>
                    <div class="number"><?php echo $stats["today_bookings"]; ?></div>
                    <small>Scheduled for today</small>
                </div>

                <div class="stat-card">
                    <h3>TOTAL REVENUE</h3>
                    <div class="number text-warm">Tk <?php echo number_format($stats["total_revenue"], 0); ?></div>
                    <small>Earned from confirmed bookings</small>
                </div>

            </div>

        </main>

    </div>

</body>
</html>
