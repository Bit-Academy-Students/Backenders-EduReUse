<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Database\Database;

$db = new Database('edureuse');
$conn = $db->connect();
$conn->query("USE edureuse");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $wachtwoord = $_POST["wachtwoord"];

    if (!empty($email) && !empty($wachtwoord)) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $loggedInUser = $stmt->fetch();

        if ($loggedInUser && password_verify($wachtwoord, $loggedInUser['wachtwoord'])) {
            $_SESSION["id"] = $loggedInUser["id"];
            header("Location: SchoolPosts.php");
            exit();
        } else {
            echo 'Invalid email and/or wachtwoord';
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">

</head>
<body>
    
    <div class="container">
    <h1>Login</h1>
    <form method="post">
        <label for="email">email</label><br>
        <input class="field" type="email" id="email" name="email"><br>
        <label for="wachtwoord">wachtwoord</label><br>
        <input class="field" type="password" id="wachtwoord" name="wachtwoord"><br>
        <input class="loginButton" type="submit" id="login" name="login" value="Login">
    </form>

    <a href="register.php">Nog geen account? klik hier om te registreren</a>
    </div>
</body>
</html>
