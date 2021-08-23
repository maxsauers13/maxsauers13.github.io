<?php
  include_once 'header.php'
?>

<!-- Trade Section START -->
<div class="container">
  <div class="row">
    <div class="col-lg-9 col-md-9">
      <?php
        if (isset($_SESSION["useruid"])) {
          echo "<h1>" . $_SESSION["usersname"] . "'s Trading Desk</h1>";
        } else {
            echo "<h1>The Trading Desk</h1>";
        }
      ?>
      <?php
            include_once 'includes/dbh.inc.php';
            include_once 'includes/functions.inc.php';

            $result = get_player($conn);
            while($player = mysqli_fetch_assoc($result)) {
              echo "<div class='news-link'><img class='poster' src='/img/jordan.png' /><span class='hot-news'>" . $player["playerPrice"] . "</span><h3 class='news-log' style='text-align: center; margin-right:60px;'>" . $player["playerName"] . "</h3><a href='buy.php' class='btn-view' style='margin-top:25px; margin-right:130px'><span class='ic-sx24'></span> Buy</a><a href='sell.php' class='btn-view' style='margin-right:150px; margin-top:25px'><span class='ic-sx24'></span> Sell</a></div>";
            }
        ?>
      </div>
    <div class="col-lg-3 col-md-3">
      <div class="right-block">
          <div class="n-m">Current Player Prices</div>
          <!-- use a for loop and echo each n-m-item -->
          <?php
            include_once 'includes/dbh.inc.php';
            include_once 'includes/functions.inc.php';

            $result = get_player($conn);
            $total = 0;
            while($player = mysqli_fetch_assoc($result)) {
              $total = $total + 1;
              echo "<div class='n-m-item'><span class='online-o'></span> " . $player["playerName"] . ": " . $player["playerPrice"] . "</div>";
            }

            echo "<p class='tdx-strom'> Total Players: " . $total . "</p>";
          ?>
      </div>
    </div>
  </div>
</div>

<?php
  include_once 'footer.php'
?>