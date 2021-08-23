<?php

// checks if the inputs are filled for the signup
function emptyInputSignup($name, $email, $username, $pwd, $pwdRepeat) {
    $result;
    
    if (empty($name) || empty($email) || empty($username) || empty($pwd) || empty($pwdRepeat)) {
        $result = true;
    }
    else {
        $result = false;
    }

    return $result;
}

// checks if the username has invalid characters
function invalidUid($username) {
    $result;
    
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $result = true;
    }
    else {
        $result = false;
    }

    return $result;
}

// checks if the email inputted was valid
function invalidEmail($email) {
    $result;
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    }
    else {
        $result = false;
    }

    return $result;
}

// checks if the pwd and pwdRepeat are matching
function pwdMatch($pwd, $pwdRepeat) {
    $result;
    
    if ($pwd !== $pwdRepeat) {
        $result = true;
    }
    else {
        $result = false;
    }

    return $result;
}

// checks if the username already exists in the database
function uidExists($conn, $username, $email) {
    $sql = "SELECT * FROM userLogins WHERE usersUid = ? OR usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

// creates a user in the database
function createUser($conn, $name, $email, $username, $pwd) {
    $sql = "INSERT INTO userLogins (usersName, usersEmail, usersUid, usersPwd) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $username, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // add the user to the balances table
    $sql = "INSERT INTO balances (username, balance) VALUES (?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $balance = 0;

    mysqli_stmt_bind_param($stmt, "ss", $username, $balance);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../signup.php?error=none");
    exit();
}

// checks if the inputs are filled for the login
function emptyInputLogin($username, $pwd) {
    $result;
    
    if (empty($username) || empty($pwd)) {
        $result = true;
    }
    else {
        $result = false;
    }

    return $result;
}

// logs the user in using the database
function loginUser($conn, $username, $pwd) {
    $uidExists = uidExists($conn, $username, $username);

    if ($uidExists === false) {
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    $pwdHashed = $uidExists["usersPwd"];
    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === false) {
        header("location: ../login.php?error=wronglogin");
        exit();
    }
    else if ($checkPwd === true) {
        session_start();
        $_SESSION["userid"] = $uidExists["user_id"];
        $_SESSION["useruid"] = $uidExists["usersUid"];
        $_SESSION["usersname"] = $uidExists["usersName"];
        $_SESSION["usersemail"] = $uidExists["usersEmail"];
        $_SESSION["userspwd"] = $pwd;
        header("location: ../index.php");
        exit();
    }
}

// gets a player from the database
function get_player($conn) {
    $sql = "SELECT * FROM players";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        # header("location: ../trade.php?error=stmtfailed");
        echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
        exit();
    }

    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    return $resultData;

    mysqli_stmt_close($stmt);
    # header("location: ../trade.php?error=none");
    exit();
}

// checks if the inputs are filled for the buy
function emptyInputBuy($name, $quantity) {
    $result;
    
    if (empty($name) || empty($quantity)) {
        $result = true;
    }
    else {
        $result = false;
    }

    return $result;
}

// checks if a player name exists in the database
function nameExists($conn, $name) {
    $sql = "SELECT * FROM players WHERE playerName = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../buy.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

// checks if the quantity inputted for a buy/sell is valid
function invalidQuantity($quantity) {
    if ($quantity <= 0) {
        return true;
    } else {
        return false;
    }
}

// check if the user is attempting an invalid trade
function invalidTrade($conn, $username, $name, $quantity) {
    // get the total bought
    $sql = "SELECT * FROM purchases WHERE username = ? AND playerName = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $name);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $totalBought = 0;
    while($trade = mysqli_fetch_assoc($resultData)) {
        $totalBought = $totalBought + $trade["quantity"];
    }

    mysqli_stmt_close($stmt);

    // get the total sold
    $sql = "SELECT * FROM sales WHERE username = ? AND playerName = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $name);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $totalSold = 0;
    while($trade = mysqli_fetch_assoc($resultData)) {
        $totalSold = $totalSold + $trade["quantity"];
    }

    if ($quantity > ($totalBought - $totalSold)) {
        return true;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
    exit();
}

// gets the current price of a player
function getPlayerPrice($conn, $name) {
    $sql = "SELECT * FROM players WHERE playerName = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../buy.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);
    return $row["playerPrice"];

    mysqli_stmt_close($stmt);
}

// creates a player purchase row in the database
function buyPlayer($conn, $username, $name, $price, $quantity) {
    $sql = "INSERT INTO purchases (username, playerName, playerPrice, quantity) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../buy.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssss", $username, $name, $price, $quantity);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // update the balance table
    $sql = "SELECT * FROM balances WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../buy.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);
    $balance = $row["balance"];

    mysqli_stmt_close($stmt);

    $sql = "UPDATE balances SET balance = ? WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../buy.php?error=stmtfailed");
        exit();
    }

    $balance = $balance - (floatval($price) * $quantity);

    mysqli_stmt_bind_param($stmt, "ss", $balance, $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../buy.php?error=none");
    exit();
}

// creates a player sale row in the database
function sellPlayer($conn, $username, $name, $price, $quantity) {
    $sql = "INSERT INTO sales (username, playerName, playerPrice, quantity) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../sell.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssss", $username, $name, $price, $quantity);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // update the balance table
    $sql = "SELECT * FROM balances WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../sell.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);
    $balance = $row["balance"];

    mysqli_stmt_close($stmt);

    $sql = "UPDATE balances SET balance = ? WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../sell.php?error=stmtfailed");
        exit();
    }

    $balance = $balance + (floatval($price) * $quantity);

    mysqli_stmt_bind_param($stmt, "ss", $balance, $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../sell.php?error=none");
    exit();
}

// get the past trades for a user
function get_purchases($conn, $username) {
    $sql = "SELECT * FROM purchases WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    return $resultData;

    mysqli_stmt_close($stmt);
    exit();
}

// get the past sales for a user
function get_sales($conn, $username) {
    $sql = "SELECT * FROM sales WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    return $resultData;

    mysqli_stmt_close($stmt);
    exit();
}

function get_user_balance($conn, $username) {
    $sql = "SELECT * FROM balances WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);
    return $row["balance"];

    mysqli_stmt_close($stmt);
    exit();
}