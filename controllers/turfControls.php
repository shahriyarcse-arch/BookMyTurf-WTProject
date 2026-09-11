<?php

session_start();

if (!isset($_SESSION["userId"]) || ($_SESSION["role"] ?? "") != "owner") {
    header("Location: ../views/login.php");
    exit();
}

require_once "../models/ownerTurfsModel.php";

$ownerId = $_SESSION["userId"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["action"] ?? "") == "add") {

    $name         = trim($_POST["name"] ?? "");
    $location     = trim($_POST["location"] ?? "");
    $description  = trim($_POST["description"] ?? "");
    $pricePerHour = trim($_POST["price_per_hour"] ?? "");

    $nameErr     = "";
    $locationErr = "";
    $priceErr    = "";
    $descErr     = "";
    $hasErr      = false;

    if (empty($name)) {
        $hasErr = true;
        $nameErr = "Turf name cannot be empty";
    }

    if (empty($location)) {
        $hasErr = true;
        $locationErr = "Location cannot be empty";
    }

    if (empty($pricePerHour) || !is_numeric($pricePerHour) || $pricePerHour <= 0) {
        $hasErr = true;
        $priceErr = "Enter a valid hourly price";
    }

    if (empty($description)) {
        $hasErr = true;
        $descErr = "Description cannot be empty";
    }

    if ($hasErr) {
        $queryParams = http_build_query([
            'nameErr'     => $nameErr,
            'locationErr' => $locationErr,
            'priceErr'    => $priceErr,
            'descErr'     => $descErr,
            'name'        => $name,
            'location'    => $location,
            'price'       => $pricePerHour,
            'desc'        => $description
        ]);
        header("Location: ../views/owner/addTurf.php?" . $queryParams);
        exit();
    } else {
        $imagePath = "views/images/default_turf.jpg";
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $allowed = ["jpg", "jpeg", "png", "webp"];
            $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $targetDir = "../views/images/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $filename = "turf_" . time() . "_" . rand(1000, 9999) . "." . $ext;
                $targetFile = $targetDir . $filename;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    $imagePath = "views/images/" . $filename;
                }
            }
        }

        $success = addTurf($ownerId, $name, $location, $description, floatval($pricePerHour), $imagePath);

        if ($success) {
            header("Location: ../views/owner/myTurfs.php?successMsg=" . urlencode("Turf listed successfully!"));
            exit();
        } else {
            header("Location: ../views/owner/addTurf.php?serverErr=" . urlencode("Failed to add turf. Please try again."));
            exit();
        }
    }
}


else if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["action"] ?? "") == "update") {

    $turfId       = intval($_POST["turf_id"] ?? 0);
    $name         = trim($_POST["name"] ?? "");
    $location     = trim($_POST["location"] ?? "");
    $description  = trim($_POST["description"] ?? "");
    $pricePerHour = trim($_POST["price_per_hour"] ?? "");
    $status       = trim($_POST["status"] ?? "active");

    if ($turfId > 0 && !empty($name) && !empty($location) && is_numeric($pricePerHour)) {
        $newImagePath = null;
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $allowed = ["jpg", "jpeg", "png", "webp"];
            $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $targetDir = "../views/images/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $filename = "turf_" . time() . "_" . rand(1000, 9999) . "." . $ext;
                $targetFile = $targetDir . $filename;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    $newImagePath = "views/images/" . $filename;
                }
            }
        }

        $success = updateTurf($turfId, $ownerId, $name, $location, $description, floatval($pricePerHour), $status, $newImagePath);

        if ($success) {
            header("Location: ../views/owner/myTurfs.php?successMsg=" . urlencode("Turf updated successfully!"));
            exit();
        } else {
            header("Location: ../views/owner/editTurf.php?id=" . $turfId . "&serverErr=" . urlencode("Update failed. Please try again."));
            exit();
        }
    } else {
        header("Location: ../views/owner/editTurf.php?id=" . $turfId . "&serverErr=" . urlencode("Please fill all required fields properly."));
        exit();
    }
}

else if (isset($_GET["action"]) && $_GET["action"] == "delete") {
    $turfId = intval($_GET["id"] ?? 0);

    if ($turfId > 0) {
        $success = deleteTurf($turfId, $ownerId);
        if ($success) {
            header("Location: ../views/owner/myTurfs.php?successMsg=" . urlencode("Turf deleted successfully!"));
            exit();
        }
    }

    header("Location: ../views/owner/myTurfs.php?errorMsg=" . urlencode("Failed to delete turf."));
    exit();
}

else {
    header("Location: ../views/owner/myTurfs.php");
    exit();
}
?>
