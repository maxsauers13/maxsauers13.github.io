<?php

if (isset($_POST["submit"])) {

    session_start();

    $name = $_POST["name"];
    $quantity = $_POST["quantity"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputBuy($name, $quantity) !== false) {
        header("location: ../buy.php?error=emptyinput");
        exit();
    }

    if (nameExists($conn, $name) == false) {
        header("location: ../buy.php?error=invalidplayer");
        exit();
    }

    if (invalidQuantity($quantity) !== false) {
        header("location: ../buy.php?error=invalidquantity");
        exit();
    }

    if (!isset($_SESSION["useruid"])) {
        header("location: ../buy.php?error=notloggedin");
        exit();
    }

    $price = getPlayerPrice($conn, $name);
    $username = $_SESSION["useruid"];
    buyPlayer($conn, $username, $name, $price, $quantity);

} else {
    header("location: ../buy.php");
    exit();
}