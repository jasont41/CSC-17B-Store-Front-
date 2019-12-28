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
    <body<link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <header><img src='assets/icon.png' height=100px width=100px></header>
    
        <?php
        require_once 'login.php';
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
        echo <<<_END


<br><br><br><br>
    <form action="sqltest.php" method="post">
    <input type="hidden" name="show_users" value="yes">
    <input type="submit" value="Show Users"></form><br>
        
    <form action='sqltest.php' method='post'>
    <input type='hidden' name='orders_placed' value="yes">
    <input type='submit' value='Show Orders Placed'></form><br>
    
_END;
        $conn = new mysqli($hn, $un, $pw, $db);
        if ($conn->connect_error)
            die($conn->connect_error);
        session_start();    //session start
        if (isset($_SESSION['username']) === 'admin') {
            echo "Hello " . $_SESSION['username'] . "<br>";
        }
        else if (isset($_SESSION['username'])!= 'admin'){     //if session not found redirect to homepage
            header('location:index.php');
        }
        else if (!isset($_SESSION['username']))
        {
            header('location:index.php');
        }
        if (isset($_POST['delete']) && isset($_POST['sku'])) {
            $sku = get_post($conn, 'sku');
            $query = "DELETE FROM items WHERE sku='$sku'";
            $result = $conn->query($query);
            if (!$result)
                echo "DELETE failed: $query<br>" .
                $conn->error . "<br><br>";
        }

        if (isset($_POST['delete_user']) && isset($_POST['name'])) {
            $name = get_post($conn, 'name');
            $query = "DELETE FROM customers WHERE name='$name'";
            $result = $conn->query($query);
            if (!$result)
                echo "DELETE failed: $query<br>" .
                $conn->error . "<br><br>";
        }


        if (isset($_POST['show_users'])) {
            
            $query = "select * from customers customer_id != ''";
            $result = $conn->query($query);
            if(!$result){
                echo "Cannot retrieve customer list at this time <BR>"; 
            }
            $rows = $result->num_rows;
            if($rows ===  0){
                echo "It appears there is no users <br>"; 
            }
            for ($j = 0; $j < $rows; ++$j) {
                $result->data_seek($j);
                $row = $result->fetch_array(MYSQLI_NUM);
                echo <<<_END
    
                Name $row[0] <br>
                Age $row[1] <br>
                Username $row[2]<br>
                Address $row[4]<br> 
            
                <form action="sqltest.php" method="post">
                <input type="hidden" name="delete_user" value="yes">
                <input type="hidden" name="name" value="$row[0]">
                <input type="submit" value="DELETE RECORD"></form>
_END;
            }
        }
        if (isset($_POST['name']) &&
                isset($_POST['price']) &&
                isset($_POST['description']) &&
                isset($_POST['sku']) &&
                isset($_POST['photo'])) {
            $name = get_post($conn, 'name');
            $price = get_post($conn, 'price');
            $description = get_post($conn, 'description');
            $sku = get_post($conn, 'sku');
            $photo = get_post($conn, 'photo');
            $query = "INSERT INTO items VALUES" .
                    "('$name', '$price', '$description', '$sku', '$photo')";
            $result = $conn->query($query);
            if (!$result)
                echo "INSERT failed: $query<br>" .
                $conn->error . "<br><br>";
        }
        echo <<<_END
 <form action="sqltest.php" method="post"><pre>
 Name        <input type="text" name="name">
 Price       <input type="text" name="price" pattern="^\d+(,\d{1,2})?$">
 Description <input type="text" name="description">
 SKU         <input type="text" name="sku">
 Photo       <input type="text" name="photo">
 <input type="submit" value="ADD RECORD">
 </pre></form>
_END;
        $query = "SELECT * FROM items";

        $result = $conn->query($query);
        if (!$result)
            die("Database access failed: " . $conn->error);
        $rows = $result->num_rows;
        for ($j = 0; $j < $rows; ++$j) {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_NUM);
            echo <<<_END
 
    Name $row[0] <br>
    Price $row[1] <br>
    Description $row[2]<br>
    Sku $row[3]<br>
    Cover<br><img src=$row[4] height=300 width=200>
   
 
     
 
    <form action="sqltest.php" method="post">
    <input type="hidden" name="delete" value="yes">
    <input type="hidden" name="sku" value="$row[3]">
    <input type="submit" value="DELETE RECORD"></form>
_END;
        }
        if(isset($_POST['orders_placed'])){
            $query="SELECT * FROM orders_placed"; 
            $result = $conn->query($query);
            if(!$result){
                echo "Something went wrong <BR>"; 
            }
            $rows = $result->num_rows; 
            for($j=0; $j<$rows;++$j){
                $result->data_seek($j); 
                $row = $result->fetch_array(MYSQLI_NUM); 
                ECHO <<<_END
                <h1>Orders placed </h1>
                <h3>
                Customer ID $row[0] <br>
                SKU  $row[1] <br> 
                Quantity $row[2] <br>
                        </h3>
_END;
            }
        }

        $result->close();
        $conn->close();

        function get_post($conn, $var) {
            return $conn->real_escape_string($_POST[$var]);
        }
        ?>
    </body>
</html>
