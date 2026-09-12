<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../views/login.php");
    exit();
}

require_once "../models/dbConnect.php";
require_once "../models/adminTurfModel.php";

$conn   = dbConnection();
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$turf = $id ? getTurfById($conn, $id) : null;

if (!$turf) {
    $_SESSION['error'] = "Turf not found.";
    mysqli_close($conn);
    header("Location: ../views/admin/manageTurfs.php");
    exit();
}

if ($action == 'approve') {
    updateTurfStatus($conn, $id, 'active');
    $_SESSION['msg'] = "Turf \"" . $turf['name'] . "\" approved. It is now active and visible to customers.";
}
elseif ($action == 'reject') {
    updateTurfStatus($conn, $id, 'rejected');
    $_SESSION['msg'] = "Turf \"" . $turf['name'] . "\" rejected.";
}
elseif ($action == 'deactivate') {
    updateTurfStatus($conn, $id, 'inactive');
    $_SESSION['msg'] = "Turf \"" . $turf['name'] . "\" deactivated.";
}
elseif ($action == 'activate') {
    updateTurfStatus($conn, $id, 'active');
    $_SESSION['msg'] = "Turf \"" . $turf['name'] . "\" activated.";
}
else {
    $_SESSION['error'] = "Unknown action.";
}

mysqli_close($conn);
header("Location: ../views/admin/manageTurfs.php");
exit();
