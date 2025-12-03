<?php

use Database\Database;

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

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

    <div class="flex flex-col justify-self-center w-[90%] max-w-300">
        <div class="flex flex-wrap justify-center items-center gap-10 my-10">
            <div class="flex flex-col w-full md:w-6/10 space-y-3 bg-white p-6 rounded-lg shadow-lg">
                <h1 class="text-3xl font-bold">
                    Wat doen wij?
                </h1>
                <p>
                    Technologie en kunst toegankelijk maken voor alle jongeren. Samen bouwen we aan een toekomst waarin elk kind, ongeacht achtergrond, de kans krijgt om te leren, te groeien en te inspireren.
                </p>
                <p>
                    Toen ik mijn eerste bedrijf begon, was ik nog maar een jongen met een idee en een missie: technologie maken die mensen helpt. Wat mij het meest raakte, was niet het succes of de media-aandacht, maar de mensen die belangeloos hielpen.
                </p>
                <p>
                    Zonder er iets voor terug te vragen, gaven zij hun tijd, kennis en vertrouwen. Dit inzicht leidde tot de <b>WailSalutem Foundation</b>, een stichting geboren uit dankbaarheid.
                </p>
            </div>

            <div class="flex flex-col w-full md:w-3/10 bg-white p-6 rounded-lg gap-3 shadow-lg">
                <h1 class="font-bold text-2xl">Een steentje bijdragen?</h1>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, nostrum. Nemo ipsa omnis
                    illum vitae, laboriosam id iusto odio. Consequatur dolores quae culpa sequi earum officiis eveniet
                    ullam dolor accusantium?
                </p>
                <a href="<?= $_SESSION['id'] ? '/school-posts' : '/register' ?>" class="bg-sky-500 text-white rounded-md p-1.5 w-30 mt-5 hover:bg-sky-600 transition">
                    Meld je nu aan
                </a>
            </div>
        </div>

        <div>
            <h1 class="font-bold text-3xl">Onze missie</h1>
        </div>
    </div>


</body>

</html>