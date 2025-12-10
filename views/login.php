<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Database\Database;

if (isset($_SESSION['id'])) {
    header('Location: /user/posts');
    exit();
}
$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_POST['email'])) {
            throw new Exception("Geen email meegegeven");
        }

        if (!isset($_POST['wachtwoord'])) {
            throw new Exception("Niet alle velden zijn ingevuld");
        }

        $email = $_POST["email"];
        $wachtwoord = $_POST["wachtwoord"];

        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $loggedInUser = $stmt->fetch();

        if (empty($loggedInUser)) {
            throw new Exception("Verkeerde email en/of wachtwoord");
        }

        if ($loggedInUser && ($wachtwoord === $loggedInUser['wachtwoord']) || password_verify($wachtwoord, $loggedInUser['wachtwoord'])) {
            $_SESSION["id"] = $loggedInUser["id"];
            header("Location: /user/posts");
            exit();
        }

        throw new Exception('Invalid email and/or wachtwoord');
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="src/output.css">
    <?php require_once __DIR__ . '/components/fontawesome-link.php' ?>
</head>

<body class="justify-center bg-gray-100">
    <?php require_once 'components/header.php' ?>

    <div class="flex flex-col w-50% p-6 shadow-lg bg-white rounded-lg justify-self-center mt-15">
        <h2 class="text-sky-600 font-bold text-3xl mb-5 text-center">Login</h2>
        <form method="post" class="space-y-6 mb-4">
            <div>
                <div>
                    <label for="email" class="cursor-pointer">Email</label>
                    <input type="text"
                        id="email" name="email"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                </div>
            </div>

            <div>
                <div>
                    <label for="wachtwoord" class="cursor-pointer">Wachtwoord</label>
                    <input
                        type="password"
                        id="wachtwoord" name="wachtwoord"
                        class="mt-2 mb-10 bg-slate-100 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                </div>
            </div>
            <button
                class="flex w-full justify-center rounded-md bg-sky-600 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-sky-500 transition"
                id="login" name="login"
                type="submit"
                value="Login">
                Login
            </button>
            <?php if (isset($_SESSION['error'])) { ?>
                <p class="font-bold text-xl p-3 rounded-md bg-red-300 text-red-600 w-fit"><?= $_SESSION['error'] ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php } ?>
        </form>
        <div>
            <a href="/register" class="text-gray-500 hover:text-black">
                Nog geen account? Klik hier om te registreren
            </a>
        </div>
    </div>
</body>

</html>