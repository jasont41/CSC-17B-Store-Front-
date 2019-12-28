<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <header><img src='assets/icon.png' height=100px width=100px></header>
    <body>
        <?php
        require_once "login.php";
        session_start(); 
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
        if (isset($_SESSION['username'])) {
            //echo "Welcome back " . $_SESSION['username'] . "<br>";
            $customer_id = $_SESSION['username'];
        }
        if (!isset($_SESSION['username'])) {
            echo "You're not logged in " . "<br>";
            $customer_id = '0';
        }
        $conn = new mysqli($hn, $un, $pw, $db);

        if ($conn->connect_error)
            die($conn->connect_error);
        if (isset($_POST['delete']) && isset($_POST['sku'])) {
            $sku = $conn->real_escape_string($_POST['sku']);
            $customer_id = $_SESSION['username'];
            $query = "DELETE FROM cart WHERE sku='$sku' and customer_id = '$customer_id'";

            $result = $conn->query($query);
            if (!$result)
                echo "DELETE failed: $query<br>" .
                $conn->error . "<br><br>";
        }
        if(isset($_SESSION['username']))
            $customer_id = $_SESSION['username']; 
        else{
            $customer_id = '0'; 
        }
        $query = "SELECT * FROM cart where customer_id = '$customer_id'";
        $result = $conn->query($query);
        if (!$result)
            die("Fatal Error");

        $rows = $result->num_rows;
        $total_price;
        for ($j = 0; $j < $rows; ++$j) {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_NUM);
            $skuTemp = $row[0];
            echo $skuTemp . "<br>";

            echo "SKU $row[0] <br>";
            echo "Name $row[1] <br>";
            echo "Price $row[2]<br>";
            echo "quantity $row[3] <br>";
            global $total_price;
            $total_price += ($row[2] * $row[3]);
            echo <<<_END
            
        <form action='cart.php' method = 'post'>
        
        <input type='hidden' name='quantity' value ='$row[3]' >
        <input type='hidden' name='sku' value ='$row[0]'>
        <input type='hidden' name='submit_order'>
        <input type='submit' value ='Order!'>
        </form>
        
    <form action="cart.php" method="post">
    <input type="hidden" name="delete" value="yes">
    <input type="hidden" name="sku" value="$skuTemp">
    <input type="submit" value="DELETE RECORD"></form>
_END;
            
            
        };
        global $total_price;
        echo "The value of everything in your cart is: $" . ($total_price / 100) . "<br>";
        if(isset($_POST['submit_order'])){
            
            echo "MADE IT THIS FAR"; 
            $customer_id = $_SESSION['username']; 
            $sku = get_post($conn, 'sku'); 
            $quantity = get_post($conn, 'quantity'); 
            echo $customer_id . $sku  . $quantity; 
            $query = "INSERT INTO orders_placed VALUES ('$customer_id','$sku','$quantity')";
            $result = $conn->query($query); 
            if(!$result){
                die;
            }
            
            header('location:placed.php'); 
        }
        function get_post($conn, $var) {
            return $conn->real_escape_string($_POST[$var]);
        }
        ?>
    </body>
</html>
