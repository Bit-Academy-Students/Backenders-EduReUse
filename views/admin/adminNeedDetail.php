<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// check if user is admin
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'id' => $_SESSION['id'],
]);

$user = $stmt->fetch();
if (!$user['is_admin']) {
    http_response_code(403);
    header('location: /404');
    exit();
}

// Get need ID from URL parameter 
$needId = $needId ?? null;

if (!$needId) {
    header('location: /admin/alles');
    exit();
}

// Get need details
$sql = "SELECT 
    needs.titel,
    needs.hoeveelheid,
    needs.postcode,
    needs.deadline,
    needs.date_created,
    needs.date_modified,
    types.type AS product_type,
    users.naam AS user_name,
    needs.user_id
FROM needs
INNER JOIN types ON needs.type_id = types.id
INNER JOIN users ON needs.user_id = users.id
WHERE needs.id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $needId]);
$need = $stmt->fetch();

if (!$need) {
    header('location: /admin/alles');
    exit();
}

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Need Details - <?= htmlspecialchars($need['titel']) ?></title>

    <link rel="stylesheet" href="/../src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>

    <!-- Geolocation links -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <div class="flex">
        <?php require_once __DIR__ . '/../components/leftSidebar.php' ?>

        <div class="bg-white p-6 rounded-lg m-5 shadow-lg w-full max-w-4xl">
            <!-- terug knop -->
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-300">
                <h1 class="font-bold text-3xl">Aanvraag Details</h1>
                <a href="/admin/<?php if ($_GET['back'] === 'all') : ?>alles<?php else : ?>aanvragen<?php endif; ?>"

                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Terug
                </a>
            </div>

            <!-- Need informatie -->
            <div class="space-y-4">

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Gebruiker</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($need['user_name'] ?? '-') ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Titel</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($need['titel'] ?? '-') ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Hoeveelheid</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($need['hoeveelheid']) ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Product Type</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($need['product_type'] ?? '-') ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Postcode</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($need['postcode']) ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Deadline</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($need['deadline'] ?? '-') ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Datum gecreëerd</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars(explode(' ', $need['date_created'])[0]) ?></p>
                </div>

                <?php if (explode(' ', $need['date_created'])[0] !== explode(' ', $need['date_modified'])[0]) : ?>
                    <div class="border-b border-gray-200 pb-3">
                        <label class="text-sm font-semibold text-gray-600 uppercase">Laatst aangepast op</label>
                        <p class="text-lg text-gray-800"><?= htmlspecialchars(explode(' ', $need['date_modified'])[0]) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="map" class="h-80"></div>

    <script>
        const api = `https://api.geoapify.com/v1/geocode/search?text=<?= htmlspecialchars(str_replace(' ', '', $need['postcode'])) ?>&apiKey=13a8e0d15e224c5ebf41734d171a7d2d`;

        let long = null;
        let lat = null;
        let stad = null;
        async function getCoordinates() {
            try {
                const response = await fetch(api);
                const data = await response.json();

                if (!response.ok || !data.features.length) {
                    alert('Er is geen locatie gevonden op deze postcode: Map wordt niet getoond');
                    console.log('Locatie niet gevonden. Kaart kan niet worden getoond');
                    return;
                }

                long = data.features[0].geometry.coordinates[1];
                lat = data.features[0].geometry.coordinates[0];
                stad = data.features[0].properties.city;
            } catch (error) {
                console.log(error);
            }

            const map = L.map('map').setView([long, lat], 14);

            const key = "<?= $_ENV['MAPTILER_API_KEY'] ?>";
            L.tileLayer(`https://api.maptiler.com/maps/streets-v4/{z}/{x}/{y}.png?key=${key}`, {
                tileSize: 512,
                zoomOffset: -1,
                minZoom: 1,
                attribution: "\u003ca href=\"https://www.maptiler.com/copyright/\" target=\"_blank\"\u003e\u0026copy; MapTiler\u003c/a\u003e \u003ca href=\"https://www.openstreetmap.org/copyright\" target=\"_blank\"\u003e\u0026copy; OpenStreetMap contributors\u003c/a\u003e",
                crossOrigin: false
            }).addTo(map);

            L.marker([long, lat]).addTo(map)
                .bindPopup(`<?= htmlspecialchars($need['postcode']) ?>, ${stad}`);
        };

        getCoordinates();
    </script>
</body>

</html>