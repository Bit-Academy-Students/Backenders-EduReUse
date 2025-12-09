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

    <div class="flex flex-col bg-white justify-self-center shadow-lg w-[90%] md:w-[80%] lg:w-[70%] gap-3 rounded-lg p-6 my-15 mx-auto">
        <h1 class="font-bold text-3xl text-center">Profiel</h1>

        <div class="flex flex-col">
            <p><b>Email</b> <?= $user['email'] ?></p>
            <p><b>Gebruikersnaam</b> <?= $user['naam'] ?></p>
            <p class="text-gray-500"><b class="text-black">Aangemaakt op</b> <?= $user['date_created'] ?></p>
        </div>
    </div>
</body>

</html>