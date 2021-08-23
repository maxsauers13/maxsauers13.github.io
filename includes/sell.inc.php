<?php

if (isset($_POST["submit"])) {

    session_start();

    $name = $_POST["name"];
    $quantity = $_POST["quantity"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputBuy($name, $quantity) !== false) {
        header("location: ../sell.php?error=emptyinput");
        exit();
    }

    if (nameExists($conn, $name) == false) {
        header("location: ../sell.php?error=invalidplayer");
        exit();
    }

    if (invalidQuantity($quantity) !== false) {
        header("location: ../sell.php?error=invalidquantity");
        exit();
    }

    if (invalidTrade($conn, $_SESSION["useruid"], $name, $quantity) !== false) {
        header("location: ../sell.php?error=invalidtrade");
        exit();
    }

    if (!isset($_SESSION["useruid"])) {
        header("location: ../sell.php?error=notloggedin");
        exit();
    }

    $price = getPlayerPrice($conn, $name);
    $username = $_SESSION["useruid"];
    sellPlayer($conn, $username, $name, $price, $quantity);

} else {
    header("location: ../sell.php");
    exit();
}