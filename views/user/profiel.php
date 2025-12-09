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
    <title>Profielpagina</title>

    <link rel="stylesheet" href="/../src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <div class="flex flex-col bg-white justify-self-center shadow-lg w-[90%] md:w-[80%] gap-6 rounded-lg p-8 my-10">
        <h1 class="font-bold text-4xl text-center text-[#5481B7] border-b-1 border-gray-300 pb-4">Profiel</h1>

        <div class="flex flex-col gap-4 text-lg">
            <p><b class="text-gray-700">Email:</b> <span class="text-black"><?= $user['email'] ?></span></p>
            <p><b class="text-gray-700">Gebruikersnaam:</b> <span class="text-black"><?= $user['naam'] ?></span></p>
            <p class="text-gray-500"><b class="text-gray-700">Aangemaakt op:</b> <span class="text-black"><?= $user['date_created'] ?></span></p>
        </div>

        <div class="flex flex-row gap-5 justify-center">
            <a href="/user/edit-profile"
                class="bg-sky-500 text-white rounded-md p-1.5 w-fit hover:bg-sky-600 transition">
                Edit profile
            </a>
            <a href="/user/change-password"
                class="bg-sky-500 text-white rounded-md p-1.5 w-fit hover:bg-sky-600 transition">
                Change password
            </a>
        </div>
    </div>
</body>

</html>