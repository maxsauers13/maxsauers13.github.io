<?php
  include_once 'header.php'
?>

<div class="container">
  <div class="row">
    <div class="col-lg-9 col-md-9">
      <section class="signup-form">
        <h2>Login</h2>
        <div style="height:10px"></div>
        <form action="includes/login.inc.php" method="post">
          <input type="text" name="uid" placeholder="Username/Email"><br>
          <div style="height:30px"></div>
          <input type="text" name="pwd" placeholder="Password"><br>
          <div style="height:30px"></div>
          <button type="submit" name="submit">Login</button>
        </form>
        <?php
          if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
              echo "<p>Fill in all of the fields.</p>";
            }
            else if ($_GET["error"] == "wronglogin") {
              echo "<p>Incorrect username/password.</p>";
            }
          }
        ?>
      </section>
    </div>
  </div>
</div>
<div style="height:440px"></div>

<?php
  include_once 'footer.php'
?>