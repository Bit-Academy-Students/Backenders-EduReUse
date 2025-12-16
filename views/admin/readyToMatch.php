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
$sql = "SELECT offers.id, offers.titel, product_states.label, offers.hoeveelheid, offers.beschrijving, offers.postcode, offers.date_created, offers.date_modified, types.type, users.naam, offers.is_completed
FROM `offers`
INNER JOIN product_states ON offers.staat_id = product_states.id
INNER JOIN types ON offers.type_id = types.id
INNER JOIN users ON offers.user_id = users.id
WHERE types.id = :id
ORDER BY offers.id DESC";
$stmt = $conn->prepare($sql);

if ($type) {
    $stmt->execute(['id' => $type['id']]);
}

$offers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$amountIncompleted = 0;
$offersAmt = 0;
foreach ($offers as $offer) {
    $offersAmt++;
    $amountIncompleted += $offer['is_completed'];
}

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ready to match</title>

    <link rel="stylesheet" href="/../src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <div class="mt-10 mb-5 bg-white w-fit justify-self-center p-3 rounded-lg shadow-md">
        <h2 class="font-semibold text-sky-600 text-2xl text-center italic">
            <?= ucfirst($need['naam']) ?> heeft <?= $need['hoeveelheid'] . " '" . lcfirst($need['type']) ?>' nodig
        </h2>
        <p class="text-slate-600">Omschrijving: <?= $need['titel'] ?></p>
    </div>


    <form action="/admin/match" method="GET">
        <div class="flex flex-col bg-white rounded-lg shadow-lg justify-self-center w-[90%] p-6">
            <?php if (empty($need)) { ?>
                <h1 class="text-center text-2xl font-bold">Geen aanvraag met id '<?= $needId ?>' gevonden..</h1>
                <?php exit(); ?>
            <?php } ?>

            <?php if (empty($offers) || $amountIncompleted === $offersAmt) { ?>
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
                    <th>Staat</th>
                    <th>Aantal</th>
                    <th>Selecteer</th>
                </tr>

                <?php foreach ($offers as $offer) { ?>
                    <?php if (!$offer['is_completed']) { // only display need if admin hasn't handled the needs
                    ?>
                        <tr class="*:border-t *:border-gray-200 *:text-center *:m-2 *:py-2 hover:bg-slate-50 cursor-pointer transition">
                            <td class="rounded-tl-lg rounded-bl-lg"><?= isset($offer['foto']) ? $offer['foto'] : '(leeg)' ?></td>
                            <td><?= $offer['naam'] ?></td>
                            <td><?= $offer['titel'] ?></td>
                            <td><?= $offer['type'] ?></td>
                            <td><?= $offer['label'] ?></td>
                            <td id="amount"><?= $offer['hoeveelheid'] ?></td>
                            <td class="rounded-tr-lg rounded-br-lg">
                                <input type="checkbox" name="selected_offers[]" value="<?= $offer['id'] ?>" class="w-4 h-4">
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </table>

            <input type="hidden" name="need_id" value="<?= $needId ?>">
            <input type="hidden" name="type_id" value="<?= $type['id'] ?>">
        </div>

        <!-- count of total selected items -->
        <div class="w-[95%] mb-3<?= (!empty($_SESSION['error'])) ? '' : 0 ?> mt-4">
            <div id="totalDiv" class="flex gap-2 justify-self-end p-4 bg-white rounded-lg shadow-md" hidden>
                <p id="total"></p>
                <span class="text-slate-500">(max <?= $need['hoeveelheid'] ?>)</span>
            </div>
        </div>

        <div class="flex gap-5 items-center justify-center fixed bottom-0 bg-slate-200 w-full p-2">
            <a href="/admin/aanvragen"
                class="text-gray-600 hover:text-black font-semibold transition">
                <i class="fa-solid fa-backward"></i>
                Terug
            </a>
            <button type="submit"
                id="submit"
                disabled
                class="bg-sky-500 font-semibold text-white px-3 py-2 hover:bg-sky-600 rounded-md transition disabled:cursor-default cursor-pointer disabled:bg-slate-400 disabled:hover:bg-slate-500 disabled:text-white">
                Match
            </button>
        </div>

        <?php if (isset($_SESSION['error'])) { ?>
            <p class="font-bold text-xl justify-self-center p-3 rounded-md bg-red-300 text-red-600 w-fit"><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php } ?>
    </form>

    <script src="/src/checkbox.js"></script>
</body>

</html>