<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../views/login.php");
    exit();
}

require_once "../models/dbConnect.php";
require_once "../models/adminUserModel.php";

$conn   = dbConnection();
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'toggle_status' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $currentAdminId = $_SESSION['userId'] ?? $_SESSION['user'] ?? 0;

    if ($id == $currentAdminId) {
        $_SESSION['error'] = "You cannot block your own admin account.";
        mysqli_close($conn);
        header("Location: ../views/admin/manageUsers.php");
        exit();
    }

    $target = getUserById($conn, $id);

    if (!$target) {
        $_SESSION['error'] = "User not found.";
        mysqli_close($conn);
        header("Location: ../views/admin/manageUsers.php");
        exit();
    }

    if ($target['role'] == 'admin') {
        $_SESSION['error'] = "Admin accounts cannot be blocked.";
        mysqli_close($conn);
        header("Location: ../views/admin/manageUsers.php");
        exit();
    }

    $new_status = ($target['status'] == 'active') ? 'blocked' : 'active';
    updateUserStatus($conn, $id, $new_status);
    $_SESSION['msg'] = "User status updated to " . $new_status . ".";
    mysqli_close($conn);
    header("Location: ../views/admin/manageUsers.php");
    exit();
}

mysqli_close($conn);
header("Location: ../views/admin/manageUsers.php");
exit();
