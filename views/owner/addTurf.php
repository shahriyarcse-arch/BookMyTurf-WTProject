<?php
session_start();
if (!isset($_SESSION["userId"]) || ($_SESSION["role"] ?? "") != "owner") {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Turf - Owner Panel</title>
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
                <a href="addTurf.php" class="nav-item active">Add Turf</a>
                <a href="myTurfs.php" class="nav-item">My Turfs</a>
                <a href="ownerBookings.php" class="nav-item">Bookings</a>
            </nav>

            <div class="logout-section">
                <a href="../logout.php" class="btn-logout">Logout</a>
            </div>
        </aside>

        <main class="right-content">
            
            <div class="welcome-box">
                <h1>Add New Turf</h1>
            </div>

            <?php if(isset($_GET["serverErr"])): ?>
                <div class="alert-box error-alert">
                    <?php echo htmlspecialchars($_GET["serverErr"]); ?>
                </div>
            <?php endif; ?>

            <div class="form-container-card">
                
                <form action="../../controllers/turfControls.php" method="post" enctype="multipart/form-data">
                    
                    <input type="hidden" name="action" value="add">

                    <div class="form-group">
                        <label for="name">Turf Name:</label>
                        <input type="text" name="name" id="name" placeholder="e.g. Dhanmondi Football Arena" value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>">
                        <span class="error-text">
                            <?php echo htmlspecialchars($_GET["nameErr"] ?? ""); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="location">Location / Address:</label>
                        <input type="text" name="location" id="location" placeholder="e.g. Road 27, Dhanmondi, Dhaka" value="<?php echo htmlspecialchars($_GET['location'] ?? ''); ?>">
                        <span class="error-text">
                            <?php echo htmlspecialchars($_GET["locationErr"] ?? ""); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="price_per_hour">Hourly Rent (in BDT):</label>
                        <input type="number" name="price_per_hour" id="price_per_hour" placeholder="e.g. 1500" value="<?php echo htmlspecialchars($_GET['price'] ?? ''); ?>">
                        <span class="error-text">
                            <?php echo htmlspecialchars($_GET["priceErr"] ?? ""); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="image">Turf Photo (Optional):</label>
                        <input type="file" name="image" id="image" accept="image/*" class="form-select" style="padding-top: 18px;">
                    </div>

                    <div class="form-group">
                        <label for="description">Description & Amenities:</label>
                        <textarea name="description" id="description" rows="4" placeholder="e.g. 7-a-side artificial turf, floodlights, changing room, drinking water available."><?php echo htmlspecialchars($_GET['desc'] ?? ''); ?></textarea>
                        <span class="error-text">
                            <?php echo htmlspecialchars($_GET["descErr"] ?? ""); ?>
                        </span>
                    </div>

                    <input type="submit" value="+ Add Turf" class="btn-submit">

                </form>

            </div>

        </main>

    </div>

</body>
</html>
