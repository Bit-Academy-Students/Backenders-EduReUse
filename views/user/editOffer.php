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
    try {
        if (! is_csrf_valid()) {
            exit();
        }

        $beschrijving = !empty($_POST['new-beschrijving']) ? htmlspecialchars($_POST["new-beschrijving"]) : throw new Exception("Geen beschrijving meegegeven");
        $hoeveelheid = !empty($_POST['new-hoeveelheid']) ? htmlspecialchars($_POST["new-hoeveelheid"]) : throw new Exception("Geen hoeveelheid opgegeven");
        $product_url = htmlspecialchars($_POST["new-link"]);
        $postcode = !empty($_POST['new-postcode']) ? htmlspecialchars(strtoupper($_POST['new-postcode'])) : throw new Exception('Geen postcode meegegeven');

        // regex voor postcode
        $pattern = '/^(\d{4})\s?([a-zA-Z]{2})$/';
        if (!preg_match($pattern, $postcode) || strlen($postcode) < 6 || strlen($postcode) > 7) {
            throw new Exception("Verkeerde postcode '$postcode' ingevoerd, houdt het format '1234 AB' aan");
        }

        // reformat postcode
        $replacement = '$1 $2';
        $postcode = preg_replace($pattern, $replacement, $postcode);

        $sql2 = "UPDATE offers SET beschrijving = :beschrijving, hoeveelheid = :hoeveelheid, product_url = :product_url, postcode = :postcode WHERE id = :id";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute(['beschrijving' => $beschrijving, 'hoeveelheid' => $hoeveelheid, 'product_url' => $product_url, 'id' => $id, 'postcode' => $postcode]);

        header("Location: /user/posts");
        exit();
    } catch (Exception $err) {
        $_SESSION['error'] = $err->getMessage();
    }
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
            <?php set_csrf(); ?>
            <div class="flex flex-col gap-4 text-lg items-center">
                <div class="flex flex-col w-full px-20">
                    <label for="new-beschrijving" class="cursor-pointer font-semibold">Beschrijving</label>
                    <textarea name="new-beschrijving" id="new-beschrijving"
                        required
                        class="bg-gray-200 p-2 rounded-md"><?= $offer['beschrijving'] ?></textarea>
                </div>
                <div class="flex flex-col w-full px-20">
                    <label for="new-hoeveelheid" class="cursor-pointer font-semibold">Hoeveelheid</label>
                    <input type="number" name="new-hoeveelheid" id="new-hoeveelheid"
                        value="<?= $offer['hoeveelheid'] ?>"
                        required
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
                        minlength="6" maxlength="7"
                        required
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
        <?php if (isset($_SESSION['error'])) { ?>
            <p class="font-bold text-xl justify-self-center p-3 rounded-md bg-red-300 text-red-600 w-fit"><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php } ?>
    </div>
</body>

</html>