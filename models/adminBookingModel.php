<?php

require_once __DIR__ . "/dbConnect.php";

function getAllBookings($conn, $status = '') {
    if ($status != '') {
        $sql  = "SELECT b.*, u.name AS customer_name, t.name AS turf_name, t.location AS turf_location
                 FROM bookings b
                 JOIN users u ON b.user_id = u.id
                 JOIN turfs t ON b.turf_id = t.id
                 WHERE b.status = ?
                 ORDER BY b.booking_date DESC, b.start_time DESC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $status);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $sql    = "SELECT b.*, u.name AS customer_name, t.name AS turf_name, t.location AS turf_location
                 FROM bookings b
                 JOIN users u ON b.user_id = u.id
                 JOIN turfs t ON b.turf_id = t.id
                 ORDER BY b.booking_date DESC, b.start_time DESC";
        $result = mysqli_query($conn, $sql);
    }

    $bookings = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
    return $bookings;
}

function countBookingsByStatus($conn, $status) {
    $sql  = "SELECT COUNT(*) AS total FROM bookings WHERE status = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $status);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row    = mysqli_fetch_assoc($result);
    return $row['total'];
}

function countAllBookings($conn) {
    $sql    = "SELECT COUNT(*) AS total FROM bookings";
    $result = mysqli_query($conn, $sql);
    $row    = mysqli_fetch_assoc($result);
    return $row['total'];
}
