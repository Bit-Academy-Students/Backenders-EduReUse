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

        $stmt = $conn->prepare('INSERT INTO users (naam, email, wachtwoord, date_created) VALUES (:naam, :email, :wachtwoord, :date_created)');
        $stmt->execute([
            'naam' => $naam,
            'email' => $email,
            'wachtwoord' => $hashed_password,
            'date_created' => $date_creared
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
</head>

<body>
    <?php require_once 'components/header.php' ?>

    <div class="container">
        <h2>Registreren</h2>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="naam">School</label>
                <input type="text" id="naam" name="naam" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="wachtwoord">wachtwoord</label>
                <input type="wachtwoord" id="wachtwoord" name="wachtwoord" required>
            </div>
            <button type="submit" name="register">Registreren</button>
        </form>
    </div>
</body>

</html>