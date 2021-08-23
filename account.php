<?php
    include_once 'header.php'
?>

<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <?php
        echo "<h1>" . $_SESSION["usersname"] . "'s Account</h1>";
      ?>
      <div class="right-block">
        <div class="n-m">Current Investments</div>
        <?php
          include_once 'includes/dbh.inc.php';
          include_once 'includes/functions.inc.php';

          // display the current investments

          $purchases = get_purchases($conn, $_SESSION["useruid"]);
          $totalInvestments = 0;
          $investmentsArray = array();
          while($purchase = mysqli_fetch_assoc($purchases)) {

            // prevent it from printing the same share statement multiple times
            if (in_array($purchase["playerName"], $investmentsArray)) {
              continue;
            }
            
            // get the total sales and purchases and compare them
            $sales = get_sales($conn, $_SESSION["useruid"]);
            $totalSold = 0;
            while($sale = mysqli_fetch_assoc($sales)) {
              if ($sale["playerName"] == $purchase["playerName"]) {
                $totalSold = $totalSold + $sale["quantity"];
              }
            }

            $tempPurchases = get_purchases($conn, $_SESSION["useruid"]);
            $totalPurchased = 0;
            while($temp = mysqli_fetch_assoc($tempPurchases)) {
              if ($temp["playerName"] == $purchase["playerName"]) {
                $totalPurchased = $totalPurchased + $temp["quantity"];
              }
            }

            if ($totalPurchased > $totalSold) {
              $totalOwned = $totalPurchased - $totalSold;
              $currentPrice = getPlayerPrice($conn, $purchase["playerName"]);
              $totalInvestments = $totalInvestments + 1;
              array_push($investmentsArray, $purchase["playerName"]);

              echo "<div class='n-m-item'> Hold " . $totalOwned . " " . $purchase["playerName"] . " share(s) at $" . $currentPrice . "</div>";
            }
          }

          echo "<p class='tdx-strom'> Total Investments: " . $totalInvestments . "</p>";
        ?>
      </div>
    </div>
    <div class="col-lg-12">
      <?php
        include_once 'includes/dbh.inc.php';
        include_once 'includes/functions.inc.php';

        $balance = get_user_balance($conn, $_SESSION["useruid"]);
        echo "<h4 style='text-align:center;'>Current Balance: $" . $balance . "</h4>";
      ?>
    </div>
    <div class="col-lg-6">
      <div class="right-block">
        <div class="n-m">Past Purchases</div>
        <?php
          include_once 'includes/dbh.inc.php';
          include_once 'includes/functions.inc.php';

          $result = get_purchases($conn, $_SESSION["useruid"]);
          $total = 0;
          while($trade = mysqli_fetch_assoc($result)) {
            $total = $total + 1;
            $purchasePrice = $trade["quantity"] * floatval($trade["playerPrice"]);
            echo "<div class='n-m-item'> Bought " . $trade["quantity"] . " " . $trade["playerName"] . " share(s) at $" . $trade["playerPrice"] . " for a total price of $" . $purchasePrice . "</div>";
          }

          echo "<p class='tdx-strom'> Total Purchases: " . $total . "</p>";
        ?>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="right-block">
        <div class="n-m">Past Sales</div>
        <?php
          include_once 'includes/dbh.inc.php';
          include_once 'includes/functions.inc.php';

          $result = get_sales($conn, $_SESSION["useruid"]);
          $total = 0;
          while($trade = mysqli_fetch_assoc($result)) {
            $total = $total + 1;
            $salePrice = $trade["quantity"] * floatval($trade["playerPrice"]);
            echo "<div class='n-m-item'> Sold " . $trade["quantity"] . " " . $trade["playerName"] . " share(s) at $" . $trade["playerPrice"] . " for a total price of $" . $salePrice . "</div>";
          }

          echo "<p class='tdx-strom'> Total Sales: " . $total . "</p>";
        ?>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="right-block">
        <div class="n-m">Account Information</div>
        <?php
          include_once 'includes/dbh.inc.php';
          include_once 'includes/functions.inc.php';

          echo "<div class='n-m-item'>Username: " . $_SESSION["useruid"] . "</div>";
          echo "<div class='n-m-item'>Email: " . $_SESSION["usersemail"] . "</div>";
          echo "<div class='n-m-item'>Password: " . $_SESSION["userspwd"] . "</div>";

        ?>
      </div>
    </div>
  </div>
</div>

<?php
    include_once 'footer.php'
?>