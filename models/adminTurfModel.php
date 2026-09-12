<?php

require_once __DIR__ . "/dbConnect.php";

function getAllTurfs($conn, $status = '') {
    if ($status != '') {
        if ($status == 'approved' || $status == 'active') {
            $sql  = "SELECT t.*, u.name AS owner_name, u.email AS owner_email
                     FROM turfs t
                     JOIN users u ON t.owner_id = u.id
                     WHERE t.status = 'active' OR t.status = 'approved'
                     ORDER BY t.id DESC";
            $result = mysqli_query($conn, $sql);
        } else {
            $sql  = "SELECT t.*, u.name AS owner_name, u.email AS owner_email
                     FROM turfs t
                     JOIN users u ON t.owner_id = u.id
                     WHERE t.status = ?
                     ORDER BY t.id DESC";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $status);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        }
    } else {
        $sql    = "SELECT t.*, u.name AS owner_name, u.email AS owner_email
                   FROM turfs t
                   JOIN users u ON t.owner_id = u.id
                   ORDER BY t.id DESC";
        $result = mysqli_query($conn, $sql);
    }

    $turfs = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $turfs[] = $row;
    }
    return $turfs;
}

function getTurfById($conn, $id) {
    $sql  = "SELECT t.*, u.name AS owner_name FROM turfs t
             JOIN users u ON t.owner_id = u.id WHERE t.id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function countTurfsByStatus($conn, $status) {
    if ($status == 'approved' || $status == 'active') {
        $sql  = "SELECT COUNT(*) AS total FROM turfs WHERE status = 'active' OR status = 'approved'";
        $result = mysqli_query($conn, $sql);
        $row    = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    $sql  = "SELECT COUNT(*) AS total FROM turfs WHERE status = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $status);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row    = mysqli_fetch_assoc($result);
    return $row['total'];
}

function countAllTurfs($conn) {
    $sql    = "SELECT COUNT(*) AS total FROM turfs";
    $result = mysqli_query($conn, $sql);
    $row    = mysqli_fetch_assoc($result);
    return $row['total'];
}

function updateTurfStatus($conn, $id, $status) {
    if ($status == 'approved') {
        $status = 'active';
    }
    $sql  = "UPDATE turfs SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    return mysqli_stmt_execute($stmt);
}
