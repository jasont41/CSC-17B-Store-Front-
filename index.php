<?php

echo "<head>";
echo "<title>It's a store</title>";
echo '<link type = "text/css" rel="stylesheet" href = "style.css">';
echo "</head>";
echo "<body>";
echo "<header>";
session_start();
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if (isset($_SESSION['username'])) {
    //echo "Welcome back " . $_SESSION['username'] . "<br>";
} else if (!isset($_SESSION['username'])) {
    $query = "DELETE FROM cart where cutomer_id='0'";
    $result = $conn->query($query);
}
echo "   <img src='assets/icon.png' height=100px width=100px>";
echo "</header>";

if (isset($_SESSION['username'])) {
    echo <<<_END
    
    <nav>
    <ul>
        <li><a href = "index.php">Home</a></li>
        <li><a href = "cart.php">Cart</a></li>
        <li><a href="logout.php">Logoff</a></li>
    <li><a href="sqltest.php">Admin</a></li>
        </ul>
    </nav>
_END;
}
if (!isset($_SESSION['username'])) {
    echo <<<_END
    <nav>
    <ul>
        <li class="selected"><a href = "index.php">Home</a></li>
        <li><a href = "cart.php">Cart</a></li>
        <li><a href="register.php">Register</a></li>
        <li><a href="user_login.php">Login</a></li>
     </ul>
    </nav>
_END;
}
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error)
    die("Fatal Error");

if (isset($_POST['add_to_cart'])) {
    if (isset($_SESSION['username'])) {
        $customer_id = $_SESSION['username'];
        echo "<h1>$customer_id</h1>";
    }
    if (!isset($_SESSION['username'])) {
        $customer_id = '0';
    }

    $sku = get_post($conn, 'sku');
    $quantity = get_post($conn, 'quantity');
    $name = get_post($conn, 'name');
    $price = get_post($conn, 'price');

    echo $customer_id . "  <br> " . $sku . "  <br> " . $quantity . " <br>  " . $price . " <br> " . $name . "<br>";
    $query = "INSERT INTO cart VALUES ('$sku','$name', '$price', '$quantity','$customer_id')";
    $result = $conn->query($query);
    if (!$result) {
        echo "DELETE failed: $query<br>" .
        mysqli_error($conn) . "<br><br>";
    }
}

function get_post($conn, $var) {
    return $conn->real_escape_string($_POST[$var]);
}

$query = "SELECT * FROM items";

$result = $conn->query($query);
if (!$result)
    die("Fatal Error");
$rows = $result->num_rows;
if ($rows === 0) {
    echo "There's no items to show";
} else if ($rows > 0) {
    for ($j = 0; $j < $rows; ++$j) {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_NUM);


        echo "Name $row[0] <br>";
        echo "Price $row[1] <br>";
        echo "Description $row[2]<br>";
        echo "Sku $row[3]<br>";
        echo "Cover<br><img src=$row[4] height=300 width=200>";
        echo "<br>";


        if (isset($_SESSION['username'])) {
            $customer_id = $_SESSION['username'];
        }
        ECHO <<<_END
        <form action = "index.php" method = "post">
        <input type = "text" name="quantity" value="1">
        
        <input type = "hidden" name = "name" value = "$row[0]">
        <input type = "hidden" name = "price" value = "$row[1]">
        <input type = "hidden" name = "sku" value = "$row[3]">
        <input type = "hidden" name = "add_to_cart" value = "yes">
        <input type = 'submit' value ="Add to cart">
   </form>
_END;
    }
    $result->close();
    $conn->close();
    echo'</body>';
}
