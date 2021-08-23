<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>NBA Trading Desk</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,300i,400,500,600" rel="stylesheet">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/fonts.css">
  </head>
  <body>

    <!-- Main Nav START -->
    <nav class="main-nav fixed">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="nav-header d-flex justify-content-between align-items-center">
              <a href="index.php" class="logo" title="LOGO">
                <img class="logo-img" src="../img/fire_logo.png" alt="LOGO">
              </a>
            </div>
            <div class="nav-wrap">
              <ul class="nav-wrap__list menu">
              <li><a href="index.php" class='btn-login'><span class='ic-sx24'></span>Home</a></li>
              <li><a href="trade.php" class='btn-login'><span class='ic-g'></span>Trade</a></li>
                <?php
                  if (isset($_SESSION["useruid"])) {
                    echo "<li><a href='account.php' class='btn-login'><span class='ic-sx21'></span>" . $_SESSION["useruid"] . "</a></li>";
                    echo "<li><a href='includes/logout.inc.php' class='btn-login'><span class='ic-sx22'></span>Logout</a></li>";
                  } else {
                    echo "<li><a href='login.php' class='btn-login'><span class='ic-sx21'></span>Login</a></li>";
                    echo "<li><a href='signup.php' class='btn-login'><span class='ic-sx22'></span>Sign Up</a></li>";
                  }
                ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </nav>
    <!-- Main Nav END -->
