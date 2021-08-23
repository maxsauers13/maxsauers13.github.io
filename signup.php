<?php
  include_once 'header.php'
?>
<div class="container">
  <div class="row">
    <div class="col-lg-9 col-md-9">
      <section class="signup-form">
        <h2>Sign Up</h2>
        <div style="height:10px"></div>
        <form action="includes/signup.inc.php" method="post">
          <input type="text" name="name" placeholder="Full Name"><br>
          <div style="height:30px"></div>
          <input type="text" name="email" placeholder="Email"><br>
          <div style="height:30px"></div>
          <input type="text" name="username" placeholder="Username"><br>
          <div style="height:30px"></div>
          <input type="text" name="pwd" placeholder="Password"><br>
          <div style="height:30px"></div>
          <input type="text" name="pwdconfirm" placeholder="Confirm Password"><br>
          <div style="height:30px"></div>
          <button type="submit" name="submit">Sign Up</button>
        </form>

        <?php
          if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
              echo "<p>Fill in all of the fields.</p>";
            }
            else if ($_GET["error"] == "invaliduid") {
              echo "<p>Your username has invalid characters.</p>";
            }
            else if ($_GET["error"] == "invalidemail") {
              echo "<p>Your email is invalid.</p>";
            }
            else if ($_GET["error"] == "passwordsdontmatch") {
              echo "<p>Your passwords don't match.</p>";
            }
            else if ($_GET["error"] == "stmtfailed") {
              echo "<p>Something went wrong. Try again.</p>";
            }
            else if ($_GET["error"] == "usernametaken") {
              echo "<p>Username already taken.</p>";
            }
            else if ($_GET["error"] == "none") {
              echo "<p>You have signed up!</p>";
            }
          }
        ?>
      </section>
    </div>
  </div>
</div>
<div style="height:260px"></div>

<?php
  include_once 'footer.php'
?>