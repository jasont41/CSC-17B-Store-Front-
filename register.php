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
        ?>

        <form action="register.php" method="post"><pre>
        Name <input type="text" name="name">
        Age <input type="text" name="age" pattern="^\d+(,\d{1,2})?$">
        Username <input type="text" name="username">
        Password <input type="text" name="password" pattern="^.{4,8}$">
        Address <input type="text" name="address">
        <input type = "hidden" name = "create_account" value = "yes">
        <input type="submit" value="create account">
            </pre></form>
        <?php
        require_once 'login.php';
        $conn = new mysqli($hn, $un, $pw, $db);
        if ($conn->connect_error)
            die($conn->connect_error);
        if (isset($_POST['create_account'])) {
            $name = get_post($conn, 'name');
            $age = get_post($conn, 'age');
            $username = get_post($conn, 'username');
            $password = get_post($conn, 'password');
            $address = get_post($conn, 'address');
            $query = "INSERT INTO customers VALUES" .
                    "('$name', '$age', '$username', '$password', '$address')";
            $result = $conn->query($query);
            header('location:index.php');
            if (!$result)
                echo "That username is already taken, try another<br>";
        }

        function get_post($conn, $var) {
            return $conn->real_escape_string($_POST[$var]);
        }
        ?>
    </body>
</html>
