<?php
session_start();
if (!isset($_SESSION['username']))
{
    header('location:index.php');
}
echo "Welcome &nbsp;";
echo $_SESSION['username'];

?>

<html>
    <head>
    </head>
    <body>
    <center>
        <h2>Welcome to Main page</h2>
        <a href="logout.php">logout</a>
    </center>
</body>
</html>