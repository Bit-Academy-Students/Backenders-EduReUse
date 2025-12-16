<?php

use Database\Database;

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

$id = (int) $_GET['id'];

$sql = "SELECT * FROM offers WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);

$offer = $stmt->fetch(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $beschrijving = $_POST["new-hoeveelheid"];
    $hoeveelheid = $_POST["new-hoeveelheid"];
    $product_url = $_POST["new-link"];
    $postcode = $_POST['new-postcode'];

    $sql2 = "UPDATE offers SET beschrijving = :beschrijving, hoeveelheid = :hoeveelheid, product_url = :product_url, postcode = :postcode WHERE id = :id";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute(['beschrijving' => $beschrijving, 'hoeveelheid' => $hoeveelheid, 'product_url' => $product_url, 'id' => $id, 'postcode' => $postcode]);


    header("Location: /user/posts");
    exit();
}

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
        <a href="/user/detail-offer?id=<?= $id ?>"
            class="text-gray-600 hover:text-black font-semibold transition">
            <i class="fa-solid fa-backward"></i>
            Terug
        </a>
        <h1 class="font-bold text-4xl text-center text-sky-600 border-b-1 border-gray-300 pb-4">Donatie info aanpassen</h1>

        <form method="post" class="flex flex-col gap-10">
            <div class="flex flex-col gap-4 text-lg items-center">
                <div class="flex flex-col w-full px-20">
                    <label for="new-name" class="cursor-pointer font-semibold">Beschrijving</label>
                    <textarea name="new-name" id="new-name"
                        class="bg-gray-200 p-2 rounded-md"><?= $offer['beschrijving'] ?></textarea>
                </div>
                <div class="flex flex-col w-full px-20">
                    <label for="new-hoeveelheid" class="cursor-pointer font-semibold">Hoeveelheid</label>
                    <input type="number" name="new-hoeveelheid" id="new-hoeveelheid"
                        value="<?= $offer['hoeveelheid'] ?>"
                        class="bg-gray-200 p-2 rounded-md">
                </div>
                <div class="flex flex-col w-full px-20">
                    <label for="new-link" class="cursor-pointer font-semibold">link naar orgineel product</label>
                    <input type="text" name="new-link" id="new-link"
                        value="<?= $offer['product_url'] ?>"
                        class="bg-gray-200 p-2 rounded-md">
                </div>
                <div class="flex flex-col w-full px-20">
                    <label for="new-postcode" class="cursor-pointer font-semibold">Postcode</label>
                    <input type="text" name="new-postcode" id="new-postcode"
                        value="<?= $offer['postcode'] ?>"
                        class="bg-gray-200 p-2 rounded-md">
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <div class="flex flex-row gap-5 items-center justify-center">
                    <input type="submit" value="Pas aan"
                        class="bg-sky-500 text-white rounded-md p-1.5 w-fit hover:bg-sky-600 cursor-pointer transition">
                </div>
            </div>
        </form>
    </div>
    <?php if (isset($_SESSION['error'])) { ?>
        <p class="font-bold text-xl justify-self-center p-3 rounded-md bg-red-300 text-red-600 w-fit"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php } ?>
</body>

</html>