<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../views/login.php");
    exit();
}

require_once "../models/dbConnect.php";
require_once "../models/adminUserModel.php";

$userId = $_SESSION['userId'] ?? $_SESSION['user'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ../views/admin/adminProfile.php");
    exit();
}

$conn   = dbConnection();
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action == 'update_info') {
    $name  = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));

    if (empty($name)) {
        $_SESSION['error'] = "Name cannot be empty.";
    } else {
        updateUserProfile($conn, $userId, $name, $phone);
        $_SESSION['name']      = $name;
        $_SESSION['user_name'] = $name;
        $_SESSION['msg']       = "Profile updated successfully.";
    }
}

elseif ($action == 'change_password') {
    $current = trim($_POST['current_password']);
    $new     = trim($_POST['new_password']);
    $confirm = trim($_POST['confirm_password']);

    $user = getUserById($conn, $userId);

    if ($user['password'] !== $current && $user['password'] !== md5($current)) {
        $_SESSION['error'] = "Current password is incorrect.";
    } elseif (strlen($new) < 4) {
        $_SESSION['error'] = "New password must be at least 4 characters.";
    } elseif ($new !== $confirm) {
        $_SESSION['error'] = "New passwords do not match.";
    } else {
        updateUserPassword($conn, $userId, $new);
        $_SESSION['msg'] = "Password changed successfully.";
    }
}

mysqli_close($conn);
header("Location: ../views/admin/adminProfile.php");
exit();
