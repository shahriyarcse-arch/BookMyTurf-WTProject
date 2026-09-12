<?php

require_once __DIR__ . "/dbConnect.php";

function getAllUsers($conn, $role = '') {
    if ($role != '') {
        $sql  = "SELECT * FROM users WHERE role = ? ORDER BY id DESC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $role);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $sql    = "SELECT * FROM users ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
    }

    $users = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    return $users;
}

function getUserById($conn, $id) {
    $sql  = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function countUsersByRole($conn, $role) {
    $sql  = "SELECT COUNT(*) AS total FROM users WHERE role = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $role);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row    = mysqli_fetch_assoc($result);
    return $row['total'];
}

function updateUserStatus($conn, $id, $status) {
    $sql  = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    return mysqli_stmt_execute($stmt);
}

function updateUserProfile($conn, $id, $name, $phone) {
    $sql  = "UPDATE users SET name = ?, phone = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $name, $phone, $id);
    return mysqli_stmt_execute($stmt);
}

function updateUserPassword($conn, $id, $newPassword) {
    $sql  = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $newPassword, $id);
    return mysqli_stmt_execute($stmt);
}
