<?php

$serverName = "127.0.0.1";
$dBUsername = "root";
$dBPassword = "Lville2019";
$dBName = "nba-trading-schema";

$conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}