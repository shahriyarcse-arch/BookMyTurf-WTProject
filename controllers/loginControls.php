<?php

require_once "../models/ownerUsersModel.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");
    $pass = trim($_POST["pass"] ?? "");

    $emailErr = "";
    $passErr = "";
    $hasErr = false; 


    if (empty($email)) {
        $hasErr = true;
        $emailErr = "Email cannot be empty";
    }


    if (empty($pass)) {
        $hasErr = true;
        $passErr = "Password cannot be empty";
    }

    if ($hasErr) {
        header("Location: ../views/login.php?emailErr=" . urlencode($emailErr) . "&passErr=" . urlencode($passErr));
        exit();
    } else {
        $user = login($email, $pass);


        if ($user) {

            session_start();
            $_SESSION["userId"]    = $user["id"];      
            $_SESSION["user"]      = $user["id"];      
            $_SESSION["name"]      = $user["name"];     
            $_SESSION["user_name"] = $user["name"];     
            $_SESSION["email"]     = $user["email"];    
            $_SESSION["role"]      = $user["role"];     


            if ($user["role"] == "owner") {
                
                header("Location: ../views/owner/ownerDashboard.php");
                exit();
            } else if ($user["role"] == "customer") {
                
                header("Location: ../views/customer/customerDashboard.php");
                exit();
            } else if ($user["role"] == "admin") {
                
                header("Location: ../views/admin/adminDashboard.php");
                exit();
            } else {
                header("Location: ../views/login.php?notFoundErr=" . urlencode("Role not defined"));
                exit();
            }
        } else {
           
            header("Location: ../views/login.php?notFoundErr=" . urlencode("Invalid email or password"));
            exit();
        }
    }
} else {
    
    header("Location: ../views/login.php");
    exit();
}
?>
