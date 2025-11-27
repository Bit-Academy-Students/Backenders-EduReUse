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
            header("Location: aanbod.php?id=".$_SESSION['id']);
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
    <link rel="stylesheet" href="src/output.css">

</head>
<body>
    <header class="bg-sky-300 p-6">
                <div>
                    <div class="flex flex-row items-center">
                        <a href="index.php">LOGO</a>
                    </div>
                </div>
            </header>
    
    <div class="flex justify-center items-center bg-sky-100 h-screen">
        
        <div class="w-96 p-6 shadow-lg bg-white rounded-lg h-100">

        <div class="flex justify-center items-center">
            <h2 class="text-sky-600 font-bold text-3xl mb-10">Login</h2>
        </div>
        <form method="post" class="space-y-6">
            <div>
                <label for="email">email</label>
                <div class="bg-slate-100 mt-2 rounded-md shadow-xs">
                    <input class="block w-full rounded-md py-1.5 px-3" type="email" id="email" name="email">
                </div>
            </div>

            <div>
                <label for="wachtwoord">wachtwoord</label>
                <div class="mt-2  mb-10  bg-slate-100 rounded-md shadow-xs">
                    <input class="block w-full rounded-md py-1.5 px-3" type="password" id="wachtwoord" name="wachtwoord">
                </div>
            </div>
            <button class="flex w-full justify-center rounded-md bg-sky-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-sky-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500" type="submit" id="login" name="login" value="Login">Login</button>
        </form>
        <div>
        <a href="register.php">Nog geen account? klik hier om te registreren</a>
        </div>
    </div>
    </div>

</body>
</html>
