<?php
session_start();    // Session start

if (isset($_POST['check'])) {    // Check form submit with IF Isset function
    $username = "admin";    // set variable value
    $password = "123";        // set variable value
    if ($_POST['username'] == $username && $_POST['password'] == $password) {   // Check Given user name, password and Variable user name password are same
        $_SESSION['username'] = $username;    // set session from given user name
        header('location:sqltest.php');
    } else {
        $err = "Authentication Failed Try again!";
    }
}
?>

<html>
    <head>
        <title>Main Page</title>
    </head>
    <body>
    <Center>
<?php if (isset($err)) {
    echo $err;
} ?>      <!-- Print Error -->

        <form method="POST" name="loginauth" target="_self">

            User Name: <input name="username" size="20" type="text" />
            <br/><br/>
            Pass Word: <input name="password" size="20" type="password" />
            <br/><br/>
            <input name="check" type="submit" value="Authenticate" />

        </form>
    </center>
</body>
</html>