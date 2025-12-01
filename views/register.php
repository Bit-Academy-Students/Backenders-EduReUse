<?php

use Database\Database;

$db = new Database('edureuse');
$conn = $db->connect();
$conn->query("USE edureuse");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $naam = $_POST["naam"];
    $wachtwoord = $_POST["wachtwoord"];
    $email = $_POST["email"];
    $date_creared = date("Y-m-d H:i:s");

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Invalid email format.";
    } else {
        $hashed_password = password_hash($wachtwoord, PASSWORD_BCRYPT);

        $stmt = $conn->prepare('INSERT INTO users (naam, email, wachtwoord, date_created, is_admin) VALUES (:naam, :email, :wachtwoord, :date_created, :is_admin)');
        $stmt->execute([
            'naam' => $naam,
            'email' => $email,
            'wachtwoord' => $hashed_password,
            'date_created' => $date_creared,
            'is_admin' => 0,
        ]);

        header("Location: /login");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" href="resources/style.css">
    <link rel="stylesheet" href="src/output.css">
</head>

<body>
    <?php require_once 'components/header.php' ?>

    <div class="container">
        <h2>Registreren</h2>
        <form method="post">
            <div class="form-group">
                <!-- <label for="naam">School</label>
                <input type="text" id="naam" name="naam" required> -->
                <div>
                    <label for="naam">School</label>
                    <div class="bg-slate-100 mt-2 rounded-md shadow-xs">
                        <input class="block w-full rounded-md py-1.5 px-3" type="text" id="naam" name="naam" required>
                    </div>
                </div>
                <div>
                    <label for="email">Email Address</label>
                    <div class="bg-slate-100 mt-2 rounded-md shadow-xs">
                        <input class="block w-full rounded-md py-1.5 px-3" type="email" id="email" name="email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="wachtwoord">wachtwoord</label>
                    <div class="bg-slate-100 mt-2 rounded-md shadow-xs">
                        <input class="block w-full rounded-md py-1.5 px-3" type="text" id="wachtwoord" name="wachtwoord" required>
                    </div>
                </div>
                <button class="flex w-full justify-center rounded-md bg-sky-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-sky-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500" type="submit" name="register">Registreren</button>
        </form>
    </div>
    <!-- <header class="bg-sky-300 p-6">
    <div>
        <div class="flex flex-row items-center">
            <a href="/index">LOGO</a>
        </div>
    </div>
</header>
<div class="flex justify-center items-center bg-sky-100 h-screen">

<div class="w-96 p-6 shadow-lg bg-white rounded-lg ">
<div class="flex justify-center items-center">
    <h2 class="text-sky-600 font-bold text-3xl mb-10">Registreren</h2>
</div>
<form action="/register" method="post" class="space-y-6"> -->
</body>

</html>