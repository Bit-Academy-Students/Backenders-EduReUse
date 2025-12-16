<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$id = (int) $_GET['id'];

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// product states
$sql = "SELECT product_states.label FROM product_states
INNER JOIN offers ON product_states.id = offers.staat_id
WHERE offers.id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);
$staten = $stmt->fetchAll(PDO::FETCH_ASSOC);

// product types
$sql = "SELECT types.type FROM types
INNER JOIN offers ON types.id = offers.type_id
WHERE offers.id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql = "SELECT * FROM offers WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);
$offers = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doneer</title>

    <link rel="stylesheet" href="/../src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>
    <div class="flex flex-col bg-white justify-self-center shadow-lg w-[40%] gap-3 rounded-lg p-6 my-15">
        <div class="">
            <a href="/user/posts"
                        class="text-gray-600 hover:text-black font-semibold transition">
                        <i class="fa-solid fa-backward"></i>
                        Terug
                    </a>
            <a class="cursor-pointer float-right" href="/user/delete-offer?id=<?= $id ?>"><i class="fa-solid fa-trash-can" style="color: #f03838;"></i></a>
            <?php foreach ($offers as $offer) { ?>
                <h2 class="font-bold text-4xl text-center text-sky-600 border-b border-gray-300 pb-4"> <?= $offer['titel'] ?> </h2>
                <?php if (!empty($offer['image_url'])) { ?>
                    <img src="../src/uploads/<?= htmlspecialchars($offer['image_url']) ?>" alt="apparaat afbeelding" class="w-full object-cover rounded-md mb-4">
                <?php } ?>
            <?php } ?>

            <?php foreach ($types as $type) { ?>
                <p ><b class="text-gray-700">Soort product: </b> <?= $type['type'] ?></p>
            <?php } ?>

            <?php foreach ($staten as $staat) { ?>
                <p><b class="text-gray-700">Staat: </b><?= $staat['label'] ?></p>
            <?php } ?>

            <?php foreach ($offers as $offer) { ?>
                <p><b class="text-gray-700">Beschrijving: </b><?= $offer['beschrijving'] ?></p>
                <p><b class="text-gray-700">Link naar orginele product: </b><a class="underline decoration-solid" href= "<?= $offer['product_url'] ?>" >Link</a></p>
                <p><b class="text-gray-700">Hoeveelheid: </b><?= $offer['hoeveelheid'] ?></p>
                <p><b class="text-gray-700">Postcode: </b><?= $offer['postcode'] ?></p>
            <?php } ?>
        </div>
        <div class="flex flex-row gap-6 justify-center">
            <a href="/user/edit-offer?id=<?= $id ?>"
                class="bg-sky-600 text-white rounded-md px-6 py-2 hover:bg-sky-500 transition flex items-center justify-center">
                Aanpassen
            </a>
        </div>
    </div>    
</body>

</html>