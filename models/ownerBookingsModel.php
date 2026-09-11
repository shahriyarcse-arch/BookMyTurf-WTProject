<?php

require_once "dbConnect.php";

function getBookingsByOwner($ownerId)
{
    $conn = dbConnection();
    $bookings = [];

    if ($conn) {
        $sql = "SELECT b.id, b.booking_date, b.start_time, b.end_time, b.total_price, b.status,
                       t.name AS turf_name, t.location AS turf_location,
                       u.name AS customer_name, u.phone AS customer_phone, u.email AS customer_email
                FROM bookings b
                JOIN turfs t ON b.turf_id = t.id
                JOIN users u ON b.user_id = u.id
                WHERE t.owner_id = ?
                ORDER BY b.id DESC";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $ownerId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $bookings[] = $row;
            }

            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
    }
    return $bookings;
}

function updateBookingStatus($bookingId, $ownerId, $newStatus)
{
    $conn = dbConnection();

    if ($conn) {
        $sql = "UPDATE bookings b
                JOIN turfs t ON b.turf_id = t.id
                SET b.status = ?
                WHERE b.id = ? AND t.owner_id = ?";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sii", $newStatus, $bookingId, $ownerId);
            $success = mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $success;
        }
        mysqli_close($conn);
    }
    return false;
}

function getOwnerDashboardStats($ownerId)
{
    $conn = dbConnection();
    $stats = [
        "total_turfs"    => 0,
        "today_bookings" => 0,
        "total_revenue"  => 0.00
    ];

    if ($conn) {
        $sqlTurfs = "SELECT COUNT(*) AS total FROM turfs WHERE owner_id = ?";
        $stmtTurfs = mysqli_prepare($conn, $sqlTurfs);
        if ($stmtTurfs) {
            mysqli_stmt_bind_param($stmtTurfs, "i", $ownerId);
            mysqli_stmt_execute($stmtTurfs);
            $res = mysqli_stmt_get_result($stmtTurfs);
            if ($row = mysqli_fetch_assoc($res)) {
                $stats["total_turfs"] = intval($row["total"]);
            }
            mysqli_stmt_close($stmtTurfs);
        }

        $sqlToday = "SELECT COUNT(*) AS today_count 
                     FROM bookings b 
                     JOIN turfs t ON b.turf_id = t.id 
                     WHERE t.owner_id = ? AND b.booking_date = CURDATE()";
        $stmtToday = mysqli_prepare($conn, $sqlToday);
        if ($stmtToday) {
            mysqli_stmt_bind_param($stmtToday, "i", $ownerId);
            mysqli_stmt_execute($stmtToday);
            $res = mysqli_stmt_get_result($stmtToday);
            if ($row = mysqli_fetch_assoc($res)) {
                $stats["today_bookings"] = intval($row["today_count"]);
            }
            mysqli_stmt_close($stmtToday);
        }

        $sqlRev = "SELECT COALESCE(SUM(b.total_price), 0) AS revenue 
                   FROM bookings b 
                   JOIN turfs t ON b.turf_id = t.id 
                   WHERE t.owner_id = ? AND b.status = 'confirmed'";
        $stmtRev = mysqli_prepare($conn, $sqlRev);
        if ($stmtRev) {
            mysqli_stmt_bind_param($stmtRev, "i", $ownerId);
            mysqli_stmt_execute($stmtRev);
            $res = mysqli_stmt_get_result($stmtRev);
            if ($row = mysqli_fetch_assoc($res)) {
                $stats["total_revenue"] = floatval($row["revenue"]);
            }
            mysqli_stmt_close($stmtRev);
        }

        mysqli_close($conn);
    }

    return $stats;
}
?>
