<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$adminDisplayName = $_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookMyTurf - Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">Book<span>MyTurf</span></div>
    <ul>
        <li><a href="adminDashboard.php">Dashboard</a></li>
        <li><a href="manageUsers.php">Users</a></li>
        <li><a href="manageTurfs.php">Turfs</a></li>
        <li><a href="adminBookings.php">Bookings</a></li>
        <li><a href="adminProfile.php"><?php echo htmlspecialchars($adminDisplayName); ?></a></li>
        <li><a href="../logout.php" style="color:#f0a500;">Logout</a></li>
    </ul>
</nav>

<div class="container">
<?php if (isset($_SESSION['msg'])): ?>
    <div class="alert-success"><?php echo htmlspecialchars($_SESSION['msg']); ?></div>
    <?php unset($_SESSION['msg']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert-error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
