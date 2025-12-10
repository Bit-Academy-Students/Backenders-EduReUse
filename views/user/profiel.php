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

    <div class="flex flex-col bg-white justify-self-center shadow-lg w-[90%] md:w-[70%] lg:w-[50%] gap-8 rounded-lg p-10 my-12 mx-auto">
        <h1 class="font-bold text-4xl text-center text-sky-600 border-b border-gray-300 pb-4">Profiel</h1>

        <div class="flex flex-col gap-6 text-lg">
            <p><b class="text-gray-700">Email:</b> <span class="text-gray-900"><?= $user['email'] ?></span></p>
            <p><b class="text-gray-700">Gebruikersnaam:</b> <span class="text-gray-900"><?= $user['naam'] ?></span></p>
            <p class="text-gray-500"><b class="text-gray-700">Aangemaakt op:</b> <span class="text-gray-900"><?= explode(' ', $user['date_created'])[0] ?></span></p>
        </div>

        <div class="flex flex-row gap-6 justify-center">
            <a href="/user/edit-profile"
                class="bg-sky-600 text-white rounded-md px-6 py-2 hover:bg-sky-500 transition flex items-center justify-center">
                Edit profile
            </a>
            <a href="/user/change-password"
                class="bg-sky-600 text-white rounded-md px-6 py-2 hover:bg-sky-500 transition flex items-center justify-center">
                Change password
            </a>
        </div>
    </div>
</body>

</html>