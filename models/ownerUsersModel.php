<?php

require_once "dbConnect.php";


function login($email, $pass)
{
    $conn = dbConnection();
    if ($conn) {

        $sql = "SELECT id, name, email, role, status FROM users WHERE email = ? AND password = ? AND status = 'active' LIMIT 1";

       
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
           
            mysqli_stmt_bind_param($stmt, "ss", $email, $pass);

          
            mysqli_stmt_execute($stmt);

          
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
            
                $user = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                return $user;
            } else {
                
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                return null;
            }
        } else {
            mysqli_close($conn);
            return null;
        }
    }
    return null;
}

function isEmailRegistered($email)
{
    $conn = dbConnection();
    if ($conn) {
        $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $exists = ($result && mysqli_num_rows($result) > 0);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $exists;
    }
    return false;
}

function registerUser($name, $email, $password, $phone, $role)
{
    if (isEmailRegistered($email)) {
        return 'exists';
    }

    $conn = dbConnection();

    if ($conn) {
        $sql = "INSERT INTO users (name, email, password, phone, role, status) VALUES (?, ?, ?, ?, ?, 'active')";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $password, $phone, $role);

            $success = mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $success;
        }
        mysqli_close($conn);
    }
    return false;
}

function getUserById($id)
{
    $conn = dbConnection();
    if ($conn) {
        $sql = "SELECT id, name, email, phone, role, status FROM users WHERE id = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $user;
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
    return null;
}
?>
