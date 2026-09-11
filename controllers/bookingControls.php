<?php
session_start();

if (!isset($_SESSION["userId"]) || ($_SESSION["role"] ?? "") != "owner") {
    header("Location: ../views/login.php");
    exit();
}

require_once "../models/ownerBookingsModel.php";

$ownerId = $_SESSION["userId"];
$action   = trim($_GET["action"] ?? "");
$bookingId = intval($_GET["id"] ?? 0);


if ($action == "confirm" && $bookingId > 0) {
    $success = updateBookingStatus($bookingId, $ownerId, "confirmed");

    if ($success) {
        header("Location: ../views/owner/ownerBookings.php?successMsg=" . urlencode("Booking #$bookingId has been CONFIRMED successfully!"));
        exit();
    } else {
        header("Location: ../views/owner/ownerBookings.php?errorMsg=" . urlencode("Failed to confirm booking."));
        exit();
    }
}

else if ($action == "reject" && $bookingId > 0) {
    $success = updateBookingStatus($bookingId, $ownerId, "rejected");

    if ($success) {
        header("Location: ../views/owner/ownerBookings.php?successMsg=" . urlencode("Booking #$bookingId has been REJECTED."));
        exit();
    } else {
        header("Location: ../views/owner/ownerBookings.php?errorMsg=" . urlencode("Failed to reject booking."));
        exit();
    }
}

else {
    header("Location: ../views/owner/ownerBookings.php");
    exit();
}
?>
