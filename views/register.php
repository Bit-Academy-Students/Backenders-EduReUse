<?php

use Database\Database;

if (isset($_SESSION['id'])) {
    header('Location: /school-posts');
    exit();
}
$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

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
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" href="src/output.css">
    <?php require_once __DIR__ . '/components/fontawesome-link.php' ?>
</head>

<body class="justify-center bg-gray-100">
    <?php require_once 'components/header.php' ?>

    <div class="flex flex-col w-50% p-6 shadow-lg bg-white rounded-lg justify-self-center mt-15">
        <h2 class="text-sky-600 font-bold text-3xl mb-5 text-center">
            Registreren
        </h2>
        <form method="post" class="space-y-6 mb-4">
            <div>
                <div>
                    <label for="naam" class="cursor-pointer">Naam / Schoolnaam</label>
                    <input type="text"
                        id="naam" name="naam"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3"
                        required>
                </div>
            </div>
            <div>
                <div class="">
                    <label for="email" class="cursor-pointer">Emailadres</label>
                    <input type="email"
                        id="email" name="email"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3"
                        required>
                </div>
            </div>
            <div>
                <div>
                    <label for="wachtwoord" class="cursor-pointer">Wachtwoord</label>
                    <input type="text"
                        id="wachtwoord" name="wachtwoord"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3"
                        required>
                </div>
            </div>
            <button
                class="flex w-full justify-center rounded-md bg-sky-600 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-sky-500 cursor-pointer transition"
                type="submit"
                name="register">
                Registreren
            </button>
        </form>
        <a href="/login" class="text-gray-500 hover:text-black">
            Heb je al een account? Klik hier om in te loggen
        </a>
    </div>
</body>

</html>