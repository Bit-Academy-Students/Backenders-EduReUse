<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());


$sql = "SELECT COUNT(*) FROM offers WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $_SESSION['id']]);
$AlleOffers = $stmt->fetchColumn();


$rows_per_page = 3;


$pages = ceil($AlleOffers / $rows_per_page);


$start = 0;

if (isset($_GET['offer-page-nr'])) {
    $page = $_GET['offer-page-nr'] - 1;
    $start = $page * $rows_per_page;
}

// offers
$sql = "SELECT * FROM offers WHERE user_id = :user_id LIMIT $start, $rows_per_page";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $_SESSION['id']]);
$offers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// needs
$sql = "SELECT
    needs.id,
    needs.titel,
    needs.hoeveelheid,
    needs.postcode,
    needs.is_completed,
    needs.deadline,
    types.type
FROM needs
INNER JOIN types ON needs.type_id = types.id
WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $_SESSION['id']]);
$needs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>

    <link rel="stylesheet" href="/src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <div class="flex flex-col bg-white rounded-lg p-8 my-12 justify-self-center w-[90%] md:w-[70%] lg:w-[60%] shadow-lg gap-6 mx-auto">
        <div class="flex flex-col gap-6">
            <h1 class="font-bold text-3xl text-sky-600">Mijn aanbiedingen</h1>
            <?php if (!$offers) { ?>
                <p class="font-semibold text-slate-500">Je hebt nog geen aanbiedingen geplaatst...</p>
            <?php } ?>
            <?php if ($offers) { ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($offers as $offer) { ?>
                        <a href="/user/detail-offer?id=<?= $offer['id'] ?>"
                            class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                            <?php if (!empty($offer['image_url'])) { ?>
                                <img src="../src/uploads/<?= htmlspecialchars($offer['image_url']) ?>"
                                    alt="apparaat afbeelding"
                                    class="w-full h-40 object-cover rounded-md mb-4">
                            <?php } ?>
                            <h2 class="font-bold text-xl text-gray-800 mb-2"> <?= $offer['titel'] ?> </h2>
                            <p class="text-sm text-gray-600">Status: In verificatie</p>
                        </a>
                <?php }
                } ?>
                </div>

                <div class="flex flex-row justify-center">
                    <?php
                    if (isset($_GET['offer-page-nr']) && $_GET['offer-page-nr'] > 1) {
                    ?>
                        <a href="?offer-page-nr=<?php echo $_GET['offer-page-nr'] - 1 ?>"><i class="fa-solid fa-angle-left" style="color: #000000;"></i></a>
                    <?php } else { ?>
                        <a><i class="fa-solid fa-angle-left" style="color: #000000;"></i></a>
                    <?php
                    }

                    ?>
                    <div class="px-4">
                        <?php

                        //Check of de GET variabel geset is
                        if (!isset($_GET['offer-page-nr'])) {
                        ?> <a class="bg-sky-600 text-white px-4 py-2 rounded-md" href="?offer-page-nr=1">1</a>
                        <?php
                            $count_from = 2;
                        } else {
                            $count_from = 1;
                        }
                        ?>

                        <?php
                        /*Bepalen hoeveel pagina nummers er getoond moeten worden op basis van de GET variabel
                            en welke tabel rijen er op de pagina getoond moeten worden */
                        for ($num = $count_from; $num <= $pages; $num++) {
                            if ($num == @$_GET['offer-page-nr']) {
                        ?> <a class="bg-sky-600 text-white px-4 py-2 rounded-md" href="?offer-page-nr=<?php echo $num ?>"><?php echo $num ?></a>
                            <?php
                            } else {
                            ?> <a class="px-4 py-2" href="?offer-page-nr=<?php echo $num ?>"><?php echo $num ?></a>
                        <?php
                            }
                        }
                        ?>
                    </div>


                    <?php if (!isset($_GET['offer-page-nr'])) { ?>
                        <a href="?offer-page-nr=2"><i class="fa-solid fa-angle-right" style="color: #000000;"></i></a>
                        <?php
                    } else {
                        if ($_GET['offer-page-nr'] >= $pages) {
                        ?>

                            <a><i class="fa-solid fa-angle-right" style="color: #000000;"></i></a>

                        <?php } else {
                        ?>
                            <a href="?offer-page-nr=<?php echo $_GET['offer-page-nr'] + 1 ?>"><i class="fa-solid fa-angle-right" style="color: #000000;"></i></a>
                    <?php }
                    } ?>
                </div>



                <div class="flex justify-center mt-6">
                    <a href="/doneer"
                        class="rounded-md bg-sky-600 px-6 py-2 text-lg font-semibold text-white hover:bg-sky-500 transition">
                        Doneer hier
                    </a>
                </div>
        </div>

        <div class="flex flex-col gap-6">
            <h1 class="font-bold text-3xl text-sky-600">Mijn aanvragen</h1>
            <?php if (!$needs) { ?>
                <p class="font-semibold text-slate-500">Je hebt nog geen aanvragen geplaatst...</p>
            <?php } ?>
            <?php if ($needs) { ?>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead class="bg-sky-100 border-b-2 border-sky-700">
                            <tr class="*:p-3 *:text-sm *:font-semibold *:tracking-wide *:text-left">
                                <th class="">Omschrijving</th>
                                <th class="">Type</th>
                                <th class="">Hoeveelheid</th>
                                <th class="">Deadline</th>
                                <th class="">Edit</th>
                                <th class="">Delete</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($needs as $need) { ?>
                                <tr class="max-h-1.5 odd:bg-white even:bg-gray-50 *:p-3 *:text-sm *:text-gray-800">
                                    <td class="overflow-hidden max-w-20"><?= $need['titel'] ?> </a> </td>
                                    <td class="overflow-hidden"> <?= $need['type'] ?> </td>
                                    <td class="overflow-hidden"> <?= $need['hoeveelheid'] ?> </td>
                                    <td class="overflow-hidden"> <?= $need['deadline'] ?> </td>
                                    <td class="overflow-hidden"><a href="/user/edit-aanvraag?id=<?= $need['id'] ?>"><i class="fa-solid fa-pen-to-square" style="color: #00a3ff;"></i></a></td>
                                    <td class="overflow-hidden"> <a class="cursor-pointer" href="/user/delete-needs?id=<?= $need['id'] ?>"><i class="fa-solid fa-trash-can" style="color: #f03838;"></i></a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>



            <div class="flex justify-center mt-6">
                <a href="/aanvraag"
                    class="rounded-md bg-sky-600 px-6 py-2 text-lg font-semibold text-white hover:bg-sky-500 transition">
                    Vraag een product aan
                </a>
            </div>
        </div>
    </div>
</body>

</html>