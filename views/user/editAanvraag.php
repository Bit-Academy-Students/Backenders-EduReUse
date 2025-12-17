<?php

use Database\Database;

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

$id = (int) $_GET['id'];

$sql = "SELECT * FROM needs WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);

$offer = $stmt->fetch(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoeveelheid = $_POST["new_need_hoeveelheid"];
    $postcode = $_POST['new_need_postcode'];
    $deadline = $_POST['new_deadline'];

    $sql2 = "UPDATE needs SET hoeveelheid = :hoeveelheid, postcode = :postcode, deadline = :deadline WHERE id = :id";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute(['hoeveelheid' => $hoeveelheid, 'postcode' => $postcode, 'deadline' => $deadline, 'id' => $id]);


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
        <a href="/user/posts"
            class="text-gray-600 hover:text-black font-semibold transition">
            <i class="fa-solid fa-backward"></i>
            Terug
        </a>
        <h1 class="font-bold text-4xl text-center text-sky-600 border-b-1 border-gray-300 pb-4">Aanvraag info aanpassen</h1>

        <form method="post" class="flex flex-col gap-10">
            <div class="flex flex-col gap-4 text-lg items-center">
                <div class="flex flex-col w-full px-20">
                    <label for="new_need_hoeveelheid" class="cursor-pointer font-semibold">Hoeveelheid</label>
                    <input type="number" name="new_need_hoeveelheid" id="new_need_hoeveelheid"
                        value="<?= $offer['hoeveelheid'] ?>"
                        class="bg-gray-200 p-2 rounded-md">
                </div>
                <div class="flex flex-col w-full px-20">
                    <label for="new_need_postcode" class="cursor-pointer font-semibold">Postcode</label>
                    <input type="text" name="new_need_postcode" id="new_need_postcode"
                        value="<?= $offer['postcode'] ?>"
                        class="bg-gray-200 p-2 rounded-md">
                </div>
            </div>
            <div class="flex flex-col w-full px-20">
                <label for="new_deadline" class="cursor-pointer font-semibold">Deadline<span class="text-gray-500">(optioneel)</span></label>
                <input type="date"
                    id="new_deadline" name="new_deadline"
                    value="<?= $offer['deadline'] ?>"
                    class="bg-gray-200 p-2 rounded-md">
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