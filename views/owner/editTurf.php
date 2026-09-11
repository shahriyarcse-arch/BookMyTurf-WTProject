<?php
session_start();
if (!isset($_SESSION["userId"]) || ($_SESSION["role"] ?? "") != "owner") {
    header("Location: ../login.php");
    exit();
}

require_once "../../models/ownerTurfsModel.php";
$ownerId = $_SESSION["userId"];
$turfId = intval($_GET["id"] ?? 0);

$turf = getTurfById($turfId, $ownerId);

if (!$turf) {
    header("Location: myTurfs.php?errorMsg=" . urlencode("Turf not found or permission denied."));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Turf - Owner Panel</title>
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
            
            <div class="welcome-box">
                <h1>Edit Turf: <?php echo htmlspecialchars($turf["name"]); ?></h1>
            </div>

            <?php if(isset($_GET["serverErr"])): ?>
                <div class="alert-box error-alert">
                    <?php echo htmlspecialchars($_GET["serverErr"]); ?>
                </div>
            <?php endif; ?>

            <div class="form-container-card">
                
                <form action="../../controllers/turfControls.php" method="post" enctype="multipart/form-data">
                    
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="turf_id" value="<?php echo $turf["id"]; ?>">

                    <div class="form-group">
                        <label for="name">Turf Name:</label>
                        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($turf["name"]); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="location">Location / Address:</label>
                        <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($turf["location"]); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="price_per_hour">Hourly Rent (in BDT):</label>
                        <input type="number" name="price_per_hour" id="price_per_hour" value="<?php echo htmlspecialchars($turf["price_per_hour"]); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Availability Status:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="active" <?php if($turf["status"] == "active") echo "selected"; ?>>Active (Available for booking)</option>
                            <option value="inactive" <?php if($turf["status"] == "inactive") echo "selected"; ?>>Inactive (Temporarily closed/maintenance)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="image">Turf Photo:</label>
                        <div style="margin-bottom: 12px;">
                            <img src="../../<?php echo !empty($turf['image']) ? htmlspecialchars($turf['image']) : 'views/images/default_turf.jpg'; ?>" 
                                 alt="Current Turf Photo" 
                                 style="width: 180px; height: 110px; object-fit: cover; border-radius: 8px; border: 2px solid #b0aba4;">
                        </div>
                        <input type="file" name="image" id="image" accept="image/*" class="form-select" style="padding-top: 18px;">
                    </div>

                    <div class="form-group">
                        <label for="description">Description & Amenities:</label>
                        <textarea name="description" id="description" rows="4" required><?php echo htmlspecialchars($turf["description"]); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <input type="submit" value="Save Changes" class="btn-submit">
                        <a href="myTurfs.php" class="btn-cancel">Cancel</a>
                    </div>

                </form>

            </div>

        </main>

    </div>

</body>
</html>
