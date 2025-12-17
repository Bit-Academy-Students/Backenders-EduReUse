<?php

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
        if (! is_csrf_valid()) {
            exit();
        }

        $naam = isset($_POST['naam']) ? htmlspecialchars($_POST['naam']) : throw new Exception("Geen naam opgegeven");
        $wachtwoord = isset($_POST["wachtwoord"]) ? htmlspecialchars($_POST['wachtwoord']) : throw new Exception("Geen wachtwoord opgegeven");
        $herhaalWachtwoord = isset($_POST['herhaal-pass']) ? htmlspecialchars($_POST['herhaal-pass']) : throw new Exception("Geen wachtwoord opgegeven");
        $email = isset($_POST["email"]) ? htmlspecialchars($_POST['email']) : throw new Exception("Geen emailadres opgegeven");
        $date_creared = date("Y-m-d H:i:s");

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        if ($wachtwoord !== $herhaalWachtwoord) {
            throw new Exception("Wachtwoorden komen niet overeen");
        }

        // check if email already exists
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($users)) {
            throw new Exception("Er bestaat al een account met dit emailadres");
        }

        // hash password
        $hashed_password = password_hash($wachtwoord, PASSWORD_BCRYPT);

        // insert into database
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
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" href="src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="justify-center bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <?php if (isset($_SESSION['error'])) { ?>
        <p class="font-bold text-xl justify-self-center p-3 mt-7 rounded-md bg-red-300 text-red-600 w-fit"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php } ?>
    <div class="flex flex-col w-50% p-6 shadow-lg bg-white rounded-lg justify-self-center my-7">
        <h2 class="text-sky-600 font-bold text-3xl mb-5 text-center">
            Registreren
        </h2>
        <form method="post" class="space-y-6 mb-4">
            <?php set_csrf(); ?>
            <div>
                <label for="naam" class="cursor-pointer">Naam / Schoolnaam</label>
                <input type="text"
                    id="naam" name="naam"
                    class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3"
                    required>
            </div>
            <div class="">
                <label for="email" class="cursor-pointer">Emailadres</label>
                <input type="email"
                    id="email" name="email"
                    class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3"
                    required>
            </div>
            <div>
                <label for="wachtwoord" class="cursor-pointer">Wachtwoord</label>
                <input type="password"
                    id="wachtwoord" name="wachtwoord"
                    class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3"
                    required>
            </div>
            <div>
                <label for="herhaal-pass" class="cursor-pointer">Herhaal wachtwoord</label>
                <input type="password"
                    id="herhaal-pass" name="herhaal-pass"
                    class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3"
                    required>
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

    <script>
        const input = document.getElementById('naam');
        input.focus();
        input.select();
    </script>
</body>

</html>