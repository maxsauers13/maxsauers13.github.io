<?php
  include_once 'header.php';
?>
<div class="container">
  <div class="row">
    <div class="col-lg-9 col-md-9">
      <section class="signup-form">
        <h2>Sell Player</h2>
        <div style="height:10px"></div>
        <form action="includes/sell.inc.php" method="post">
          <input type="text" name="name" placeholder="Player Name"><br>
          <div style="height:30px"></div>
          <input type="text" name="quantity" placeholder="Quantity"><br>
          <div style="height:30px"></div>
          <button type="submit" name="submit">Sell</button>
        </form>
        <?php
          if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
              echo "<p>Fill in all of the fields.</p>";
            }
            else if ($_GET["error"] == "invalidplayer") {
              echo "<p>Player name does not exist.</p>";
            }
            else if ($_GET["error"] == "invalidquantity") {
              echo "<p>Quantity is invalid.</p>";
            }
            else if ($_GET["error"] == "invalidtrade") {
              echo "<p>You haven't bought enough to complete trade.</p>";
            }
            else if ($_GET["error"] == "notloggedin") {
              echo "<p>Login to complete a sale.</p>";
            }
            else if ($_GET["error"] == "none") {
              echo "<p>Your sale is complete!</p>";
            }
          }
        ?>
      </section>
    </div>
  </div>
</div>
<div style="height:440px"></div>

<?php
  include_once 'footer.php';
?>