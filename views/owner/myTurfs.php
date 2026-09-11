<?php
session_start();
if (!isset($_SESSION["userId"]) || ($_SESSION["role"] ?? "") != "owner") {
    header("Location: ../login.php");
    exit();
}

require_once "../../models/ownerTurfsModel.php";
$ownerId = $_SESSION["userId"];
$myTurfs = getTurfsByOwner($ownerId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Turfs - Owner Panel</title>
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
                <a href="myTurfs.php" class="nav-item active">My Turfs</a>
                <a href="ownerBookings.php" class="nav-item">Bookings</a>
            </nav>

            <div class="logout-section">
                <a href="../logout.php" class="btn-logout">Logout</a>
            </div>
        </aside>

        <main class="right-content">
            
            <div class="welcome-box header-with-btn">
                <div>
                    <h1>My Listed Turfs</h1>
                </div>
                <div>
                    <a href="addTurf.php" class="btn-new-turf">+ Add Another Turf</a>
                </div>
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
                
                <?php if(empty($myTurfs)): ?>
                    <div class="empty-box">
                        <h3>No Turfs Listed Yet!</h3>
                        <p>You haven't added any sports turf yet. Click the <b>"+ Add Another Turf"</b> button above to create your first listing.</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Turf Name</th>
                                <th>Location</th>
                                <th>Hourly Rate</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 1;
                            foreach($myTurfs as $turf): 
                            ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td>
                                        <img src="../../<?php echo !empty($turf['image']) ? htmlspecialchars($turf['image']) : 'views/images/default_turf.jpg'; ?>" 
                                             alt="Turf" 
                                             style="width: 80px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                                    </td>
                                    <td><b><?php echo htmlspecialchars($turf["name"]); ?></b></td>
                                    <td><?php echo htmlspecialchars($turf["location"]); ?></td>
                                    <td><b style="color: #d97706;">Tk <?php echo number_format($turf["price_per_hour"], 2); ?></b>/hr</td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($turf["status"]); ?>">
                                            <?php echo ucfirst($turf["status"]); ?>
                                        </span>
                                    </td>
                                    <td class="actions-cell">
                                        <a href="editTurf.php?id=<?php echo $turf["id"]; ?>" class="action-btn edit-btn">Edit</a>
                                        
                                        <a href="../../controllers/turfControls.php?action=delete&id=<?php echo $turf["id"]; ?>" 
                                           class="action-btn delete-btn" 
                                           onclick="return confirm('Are you sure you want to delete this turf?');">
                                           Delete
                                        </a>
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
