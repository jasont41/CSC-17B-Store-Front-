<?php

echo <<<_END
 <a href = "sqltest.php"><button type = "button">Admin Page</button></a> 
        <a href = "index.php"><button type="button">Home</button></a> 
        <a href= "cart.php"><button type=button">Cart</button></a><br>
_END;


require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error)
    die("Fatal Error");

$query = "SELECT * FROM items";
$result = $conn->query($query);
if (!$result)
    die("Fatal Error");

if (isset($_POST['add_to_cart']) && isset($_POST['sku']) && isset($_POST['name']) && isset($_POST['price'])) {
    $sku = get_post($conn, 'sku');
    $name = get_post($conn, 'name');
    $price = get_post($conn, 'price');
    $query = "INSERT INTO cart VALUES" . "('$sku','$name','$price')";
    $result = $conn->query($query);
    if (!$result) {
        echo "DELETE failed: $query<br>" .
        $conn->error . "<br><br>";
    }


    $rows = $result->num_rows;

    for ($j = 0; $j < $rows; ++$j) {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_NUM);


        echo "Name $row[0] <br>";
        echo "Price $row[1] <br>";
        echo "Description $row[2]<br>";
        echo "Sku $row[3]<br>";
        echo "Cover<br><img src=$row[4] height=300 width=200>";
        echo "<br>";
        echo <<<_END
 
    
   
 
     
 
                <form action="index.php" method="post">
                <input type="hidden" name="add_to_cart" value="yes">
                <input type="hidden" name="name" value="$row[0]">
                <input type="hidden" name="price" value="$row[1]">
                <input type="hidden" name="sku" value="$row[3]">
                <input type="submit" value="Add to Cart"></form>
_END;

        if (!$result) {
            echo "INSERT failed: $query<br>" .
            $conn->error . "<br><br>";
        }
        $result->close();
        $conn->close();
    }
}
?>

