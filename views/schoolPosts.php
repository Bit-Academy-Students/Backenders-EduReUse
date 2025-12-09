<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

$id = $_SESSION['id'];

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$recordset = $stmt->execute(['id' => $id]);

$sql2 = "SELECT * FROM needs WHERE user_id = :user_id";
$stmt2 = $conn->prepare($sql2);
$recordset2 = $stmt2->execute(['user_id' => $id]);

$sql3 = "SELECT * FROM offers WHERE user_id = :user_id";
$stmt3 = $conn->prepare($sql3);
$recordset3 = $stmt3->execute(['user_id' => $id]);

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link rel="stylesheet" href="src/output.css">
    <?php require_once __DIR__ . '/components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once 'components/header.php' ?>

    <div class="flex flex-col bg-white rounded-lg p-6 my-15 justify-self-center w-[60%] shadow-lg gap-10">
        <div class="flex flex-col gap-2">
            <h1 class="font-bold text-2xl">Mijn aanbiedingen</h1>
            <div class="flex flex-row">
                <?php while ($offer = $stmt3->fetch()) : ?>
                    <div class="">
                        <img src="<?= $offer['image_url'] ?>" alt="apparaat afbeelding" class="w-40">
                        <h2 class="font-bold text-2xl"><?= $offer['titel'] ?></h2>
                        <p>Status</p>
                    </div>
                <?php endwhile; ?>
               
            </div>

            <div>
                <a href="/doneer"
                class="rounded-md bg-sky-600 px-3 py-1.5 text-sm/6 font-semibold text-white w-26 cursor-pointer hover:bg-sky-500 transition">
                Doneer hier
                </a>
            </div>
        </div>
        
        <div class="flex flex-col gap-2">
            <h1 class="font-bold text-2xl">Mijn aanvragen</h1>
            <div class="flex flex-row items-center gap-4">
                <table class="w-100">
                    <thead class="bg-sky-100 border-b-2 border-sky-700">
                        <tr class="">
                            <th class="w-50 p-3 text-sm font-semibold tracking-wide ">Model</th>
                            <th class="w-10 p-3 text-sm font-semibold tracking-wide ">Hoeveelheid</th>
                            <th class="w-30 p-3 text-sm font-semibold tracking-wide ">status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($need = $stmt2->fetch()) : ?>
                            <tr class=" "> 
                                <td class="p-3 text-sm"> <?=$need['titel']?> </td>
                                <td class="p-3 text-sm"> <?=$need['hoeveelheid']?> </td>
                                <td class="p-3 text-sm">Open</td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div>
                <a href="/aanvraag"
                    class="rounded-md bg-sky-600 px-3 py-1.5 text-sm/6 font-semibold text-white w-45 cursor-pointer hover:bg-sky-500 transition">
                    Vraag een product aan
                </a>
            </div>
        
        </div>
    </div>

</body>

<body>
