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
        <form action="user_login.php" method ="post">
            Username <input type="text" name="username">
            Password <input type="password" name="password">
            <input type="hidden" name="user_login" value="yes">
            <input type="submit" value="Login!">
        </form> 
_END;
            $conn = new mysqli($hn, $un, $pw, $db);
            if ($conn->connect_error)
                die($conn->connect_error);
            if (isset($_POST['user_login'])) {
                $username = get_post($conn, 'username');
                $password = get_post($conn, 'password');
                $query = "select * from customers where username = '$username' and password = '$password'";
                $result = $conn->query($query);
                $count = mysqli_num_rows($result);
                if ($result) {
                    session_start();
                    $_SESSION['username'] = $username;
                    header('location:index.php');
                    //echo $_SESSION['username'];
                }
                if (!$result) {
                    echo "Either your password or username wasn't correct <br>";
                }
            }

            function get_post($conn, $var) {
                return $conn->real_escape_string($_POST[$var]);
            }
            ?>
    </body>
</html>
