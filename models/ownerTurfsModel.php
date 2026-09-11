<?php

require_once "dbConnect.php";
function addTurf($ownerId, $name, $location, $description, $pricePerHour, $image = "")
{
    $conn = dbConnection();
    if ($conn) {

        $sql = "INSERT INTO turfs (owner_id, name, location, description, price_per_hour, image, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "isssds", $ownerId, $name, $location, $description, $pricePerHour, $image);
            $success = mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $success;
        }
        mysqli_close($conn);
    }
    return false;
}

function getTurfsByOwner($ownerId)
{
    $conn = dbConnection();
    $turfs = [];

    if ($conn) {
        $sql = "SELECT id, owner_id, name, location, description, price_per_hour, image, status FROM turfs WHERE owner_id = ? ORDER BY id DESC";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $ownerId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $turfs[] = $row;
            }

            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
    }
    return $turfs;
}

function getTurfById($id, $ownerId)
{
    $conn = dbConnection();
    if ($conn) {
        $sql = "SELECT id, owner_id, name, location, description, price_per_hour, image, status FROM turfs WHERE id = ? AND owner_id = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $id, $ownerId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $turf = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                return $turf;
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
    }
    return null;
}

function updateTurf($id, $ownerId, $name, $location, $description, $pricePerHour, $status, $image = null)
{
    $conn = dbConnection();
    if ($conn) {
        if (!empty($image)) {
            $sql = "UPDATE turfs SET name = ?, location = ?, description = ?, price_per_hour = ?, status = ?, image = ? WHERE id = ? AND owner_id = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sssdssii", $name, $location, $description, $pricePerHour, $status, $image, $id, $ownerId);
                $success = mysqli_stmt_execute($stmt);

                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                return $success;
            }
        } else {
            $sql = "UPDATE turfs SET name = ?, location = ?, description = ?, price_per_hour = ?, status = ? WHERE id = ? AND owner_id = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sssdsii", $name, $location, $description, $pricePerHour, $status, $id, $ownerId);
                $success = mysqli_stmt_execute($stmt);

                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                return $success;
            }
        }
        mysqli_close($conn);
    }
    return false;
}

function deleteTurf($id, $ownerId)
{
    $conn = dbConnection();
    if ($conn) {
        $sql = "DELETE FROM turfs WHERE id = ? AND owner_id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $id, $ownerId);
            $success = mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $success;
        }
        mysqli_close($conn);
    }
    return false;
}
?>