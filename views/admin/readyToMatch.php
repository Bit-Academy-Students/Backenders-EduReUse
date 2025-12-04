<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}
$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// get type from database
$sql = "SELECT * FROM types WHERE type = :type";
$stmt = $conn->prepare($sql);
$stmt->execute(['type' => $typeLabel]);
$type = $stmt->fetch(PDO::FETCH_ASSOC);

// get need from database
$sql = "SELECT needs.id, needs.titel, needs.hoeveelheid, needs.postcode, needs.deadline, needs.date_created, needs.date_modified, types.type, users.naam
FROM `needs`
INNER JOIN types ON needs.type_id = types.id
INNER JOIN users ON needs.user_id = users.id
WHERE needs.id = :need_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['need_id' => $needId]);
$need = $stmt->fetch(PDO::FETCH_ASSOC);

// get offers with correct type from database
$sql = "SELECT offers.id, offers.titel, product_states.label, offers.hoeveelheid, offers.beschrijving, offers.postcode, offers.date_created, offers.date_modified, types.type, users.naam
FROM `offers`
INNER JOIN product_states ON offers.staat_id = product_states.id
INNER JOIN types ON offers.type_id = types.id
INNER JOIN users ON offers.user_id = users.id
WHERE types.type = :type
ORDER BY offers.id DESC";
$stmt = $conn->prepare($sql);

if ($type) {
    $stmt->execute(['type' => $type['type']]);
}

$offers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// echo 'type';
// print_r($type);
// echo 'need';
// print_r($need);
// echo 'offers';
// print_r($offers);
// echo '</pre>';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ready to match</title>

    <link rel="stylesheet" href="/../src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <h2 class="mt-10 font-semibold text-sky-600 text-2xl text-center mb-5 italic bg-white w-fit justify-self-center p-3 rounded-lg shadow-md">
        <?= ucfirst($need['naam']) ?> heeft <?= $need['hoeveelheid'] . " '" . lcfirst($need['type']) ?>' nodig
    </h2>

    <form action="/admin/match" method="POST">
        <div class="flex flex-col bg-white rounded-lg shadow-lg justify-self-center w-[90%] mb-10 p-6">
            <?php if (empty($need)) { ?>
                <h1 class="text-center text-2xl font-bold">Geen aanvraag met id '<?= $needId ?>' gevonden..</h1>
                <?php exit(); ?>
            <?php } ?>
            <?php if (empty($offers)) { ?>
                <?php if ($type) { ?>
                    <h1 class="text-center text-2xl font-bold">Niemand heeft nog een donatie met het type '<?= $type['type'] ?>' aangemaakt</h1>
                    <?php exit(); ?>
                <?php } else { ?>
                    <h1 class="text-center text-2xl font-bold">Geen aanbiedingen voor '<?= $typeLabel ?>' gevonden..</h1>
                    <?php exit(); ?>
                <?php } ?>
            <?php } ?>

            <table>
                <tr class="*:pb-4 *:text-xl">
                    <th>Foto</th>
                    <th>(School)naam</th>
                    <th>Omschrijving</th>
                    <th>Type</th>
                    <th>Aantal</th>
                    <th>Selecteer</th>
                </tr>

                <?php foreach ($offers as $offer) { ?>
                    <tr class="*:border-t *:border-gray-200 *:text-center *:m-2 *:py-2 hover:bg-slate-50 transition">
                        <td class="rounded-tl-lg rounded-bl-lg"><?= isset($offer['foto']) ? $offer['foto'] : '(leeg)' ?></td>
                        <td><?= $offer['naam'] ?></td>
                        <td><?= $offer['titel'] ?></td>
                        <td><?= $offer['type'] ?></td>
                        <td><?= $offer['hoeveelheid'] ?></td>
                        <td class="rounded-tr-lg rounded-br-lg">
                            <input type="checkbox" name="selected_offers[]" value="<?= $offer['id'] ?>" class="w-4 h-4">
                        </td>
                    </tr>
                <?php } ?>
            </table>

            <input type="hidden" name="need_id" value="<?= $needId ?>">
            <input type="hidden" name="type_id" value="<?= $type['id'] ?>">
        </div>

        <div class="flex gap-5 items-center justify-center absolute bottom-0 bg-slate-200 w-full p-6">
            <a href="/admin/aanvragen"
                class="text-gray-600 hover:text-black font-semibold transition">
                <i class="fa-solid fa-backward"></i>
                Terug
            </a>
            <button type="submit" class="bg-sky-500 font-semibold text-white p-4 hover:bg-sky-600 rounded-md transition">
                Match
            </button>
        </div>

        <?php if (isset($_SESSION['error'])) {
            echo $_SESSION['error'];
            unset($_SESSION['error']);
        } ?>

    </form>

    <script src="/src/checkbox.js"></script>
</body>

</html>