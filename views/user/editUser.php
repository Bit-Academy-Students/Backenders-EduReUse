<?php

use Database\Database;

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $_SESSION['id']]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord wijzigen</title>

    <link rel="stylesheet" href="/../src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <div class="flex flex-col bg-white justify-self-center shadow-lg w-[60%] md:w-[50%] gap-6 rounded-lg p-8 my-10">
        <h1 class="font-bold text-4xl text-center text-[#5481B7] border-b-1 border-gray-300 pb-4">Wijzig gegevens</h1>

        <form method="post" class="flex flex-col gap-10">
            <div class="flex flex-col gap-4 text-lg items-center">
                <div class="flex flex-col w-full px-20">
                    <label for="new-name" class="cursor-pointer font-semibold">Nieuwe (school)naam</label>
                    <input type="text" name="new-name" id="new-name"
                        placeholder="naam"
                        value="<?= $user['naam'] ?>"
                        class="bg-gray-200 p-2 rounded-md">
                </div>
                <div class="flex flex-col w-full px-20">
                    <label for="new-email" class="cursor-pointer font-semibold">Nieuw emailadres</label>
                    <input type="text" name="new-email" id="new-email"
                        placeholder="emailadres"
                        value="<?= $user['email'] ?>"
                        class="bg-gray-200 p-2 rounded-md">
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <div class="flex flex-row gap-5 items-center justify-center">
                    <a href="/user"
                        class="text-gray-600 hover:text-black font-semibold transition">
                        <i class="fa-solid fa-backward"></i>
                        Terug
                    </a>
                    <input type="submit" value="Pas aan"
                        class="bg-sky-500 text-white rounded-md p-1.5 w-fit hover:bg-sky-600 cursor-pointer transition">
                </div>

                <p class="text-gray-400 text-center">Na het wijzigen van je gegevens, moet je opnieuw inloggen</p>
            </div>
        </form>
    </div>
    <?php if (isset($_SESSION['error'])) { ?>
        <p class="font-bold text-xl justify-self-center p-3 rounded-md bg-red-300 text-red-600 w-fit"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php } ?>
</body>

</html>