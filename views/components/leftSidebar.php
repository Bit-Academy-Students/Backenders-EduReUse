<?php

$uri = $_SERVER['REQUEST_URI'];
$e = explode('/admin/', $uri);
$page = $e[1];

?>
<aside class="flex flex-col gap-4 my-10 ml-4 w-1/10 p-2">
    <a href="/admin/alles">
        <h2 class="text-2xl hover:font-bold <?= ($page === 'alles' || (isset($_GET['back']) && $_GET['back'] === 'all')) ? 'font-bold' : '' ?>">
            Alles
        </h2>
    </a>

    <a href="/admin/aanvragen">
        <h2 class="text-2xl hover:font-bold <?= ($page === 'aanvragen' || (isset($_GET['back']) && $_GET['back'] === 'needs')) ? 'font-bold' : '' ?>">
            Aanvragen
        </h2>
    </a>

    <a href="/admin/matches">
        <h2 class="text-2xl hover:font-bold <?= ($page === 'matches' || explode('/', $page)[0] === 'matches') ? 'font-bold' : '' ?>">
            Matches
        </h2>
    </a>

    <a href="/admin/gebruikers">
        <h2 class="text-2xl hover:font-bold <?= ($page === 'gebruikers') ? 'font-bold' : '' ?>">
            Gebruikers
        </h2>
    </a>
</aside>