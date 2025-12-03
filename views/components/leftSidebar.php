<?php $uri = $_SERVER['REQUEST_URI']; ?>
<?php $e = explode('/admin/', $uri) ?>
<?php $page = $e[1]; ?>

<aside class="flex flex-col gap-4 my-10 ml-4 ">
    <a href="/admin/alles">
        <h2 class="text-2xl hover:font-bold <?= ($page === 'alles') ? 'font-bold' : '' ?>">
            Alles
        </h2>
    </a>

    <a href="/admin/aanbiedingen">
        <h2 class="text-2xl hover:font-bold <?= ($page === 'aanbiedingen') ? 'font-bold' : '' ?>">
            Aanbiedingen
        </h2>
    </a>

    <a href="/admin/aanvragen">
        <h2 class="text-2xl hover:font-bold <?= ($page === 'aanvragen') ? 'font-bold' : '' ?>">
            Aanvragen
        </h2>
    </a>

    <a href="/admin/matches">
        <h2 class="text-2xl hover:font-bold <?= ($page === 'matches') ? 'font-bold' : '' ?>">
            Matches
        </h2>
    </a>

    <a href="/admin/planning">
        <h2 class="text-2xl hover:font-bold <?= ($page === 'planning') ? 'font-bold' : '' ?>">
            Planning
        </h2>
    </a>
</aside>