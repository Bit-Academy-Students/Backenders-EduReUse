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
            <div class="flex flex-col w-full md:w-3/10 bg-white p-8 rounded-lg gap-3 shadow-lg">
                <h1 class="text-3xl font-bold">
                    Wie ben ik?
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

            <div class="flex flex-col w-full md:w-3/10 bg-white p-8 rounded-lg gap-4 shadow-lg">
                <h1 class="font-bold text-3xl">Wat we doen</h1>
                <h3 class="font-bold text-xl">Coderen</h3>
                <p>
                    In onze stichting draait alles om praktijkgericht leren, waarin technologie, kunst en burgerschap samenkomen. We gebruiken het STEAM-model, altijd door de lens van Hoofd, Hart en Handen.
                </p>
                <h3 class="font-bold text-xl">Robotica</h3>
                <p>Hands-on ervaring met het bouwen en programmeren van robots, waardoor technische concepten tastbaar worden.</p>
                <h3 class="font-bold text-xl">Kunstmatige Intelligentie</h3>
                <p>Kennismaken met de principes en mogelijkheden van AI, inclusief ethische vraagstukken die hierbij komen kijken.</p>
            </div>

            <div class="flex flex-col w-full md:w-3/10 bg-white p-6 rounded-lg gap-3 shadow-lg">
                <h3 class="font-bold text-2xl">Waarom we dit doen</h3>
                <p>“Als ik een jongere zie stralen na het bouwen van zijn eerste robot, of een meisje haar moeder trots hoor uitleggen wat AI is, dan voel ik waarvoor ik dit doe.”</p>
                <p>Het gaat niet om wat je verdient, maar om wie je helpt. Niet om macht, maar om vertrouwen. Niet om status, maar om impact. Als je als jongere één keer hebt gevoeld dat je ertoe doet, dat iemand in jou gelooft, dan verander je. En dan kun je later ook zelf iemand zijn die het verschil maakt.</p>
                <h3 class="font-bold text-2xl">Het Sociaal Innovatie Lab</h3>
                <p>Onze trots is het WailSalutem Social Innovation Lab. Een broedplaats waar jongeren wekelijks samenkomen om te experimenteren en oplossingen te bouwen voor maatschappelijke uitdagingen.</p>
            </div>

        </div>

        <div class="flex flex-col w-full md:w-5/10 bg-white p-6 rounded-lg gap-3 shadow-lg">
            <h1 class="font-bold text-xl">Bijdrage doen?</h1>
            <p>Wil je ons werk steunen? Overweeg dan een donatie. Samen kunnen we meer jongeren bereiken en inspireren.</p>
            <a href="<?= isset($_SESSION['id']) ? '/user/posts' : '/register' ?>" class="bg-sky-500 text-white rounded-md p-1.5 w-35 mt-1 hover:bg-sky-600 transition">
                Meld je nu aan!
            </a>
        </div>
    </div>


</body>

</html>