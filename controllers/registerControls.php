<?php

require_once "../models/ownerUsersModel.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name        = trim($_POST["name"] ?? "");
    $email       = trim($_POST["email"] ?? "");
    $phone       = trim($_POST["phone"] ?? "");
    $pass        = trim($_POST["pass"] ?? "");
    $confirmPass = trim($_POST["confirmPass"] ?? "");
    $role        = trim($_POST["role"] ?? "customer");

    if ($role != "owner" && $role != "customer") {
        $role = "customer";
    }

    $nameErr        = "";
    $emailErr       = "";
    $phoneErr       = "";
    $passErr        = "";
    $confirmPassErr = "";
    $hasErr         = false;

    if (empty($name)) {
        $hasErr = true;
        $nameErr = "Name cannot be empty";
    }

    if (empty($email)) {
        $hasErr = true;
        $emailErr = "Email cannot be empty";
    }

    if (empty($phone)) {
        $hasErr = true;
        $phoneErr = "Phone number cannot be empty";
    }

    if (empty($pass)) {
        $hasErr = true;
        $passErr = "Password cannot be empty";
    }


    if ($pass != $confirmPass) {
        $hasErr = true;
        $confirmPassErr = "Passwords do not match";
    }


    if ($hasErr) {
        $queryParams = http_build_query([
            'nameErr'        => $nameErr,
            'emailErr'       => $emailErr,
            'phoneErr'       => $phoneErr,
            'passErr'        => $passErr,
            'confirmPassErr' => $confirmPassErr,
            'name'           => $name,
            'email'          => $email,
            'phone'          => $phone,
            'role'           => $role
        ]);
        header("Location: ../views/register.php?" . $queryParams);
        exit();
    } else {

        $result = registerUser($name, $email, $pass, $phone, $role);

        if ($result === 'exists') {
            header("Location: ../views/register.php?existErr=" . urlencode("This email is already registered! Please login.") . "&name=" . urlencode($name) . "&phone=" . urlencode($phone) . "&role=" . urlencode($role));
            exit();
        } 
        else if ($result === true) {
            header("Location: ../views/login.php?successMsg=" . urlencode("Account created successfully! You can now login."));
            exit();
        } 

        else {
            header("Location: ../views/register.php?serverErr=" . urlencode("Registration failed. Please try again."));
            exit();
        }
    }

} else {

    header("Location: ../views/register.php");
    exit();
}
?>
