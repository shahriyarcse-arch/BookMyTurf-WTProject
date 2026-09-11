<?php

$serverName = "localhost";
$userName = "root";
$password = "";
$db = "bookmyturf";

function dbConnection()
{
    global $serverName, $userName, $password, $db;
    $conn = mysqli_connect($serverName, $userName, $password, $db);
    if ($conn) {
        return $conn;
    } else {
        die("Database connection failed: " . mysqli_connect_error());
    }
}
?>