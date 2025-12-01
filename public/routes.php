<?php

use Controllers\DonateController;
use Controllers\NeedController;

require_once __DIR__ . '/router.php';

// homepage
get('/', 'views/home.php');

// donaties
get('/doneer', 'views/doneer.php');
post('/doneer', function () {
    try {
        // voeg donatie toe aan database
        $controller = new DonateController();
        $controller->post();
    } catch (Exception $e) {
        $_SESSION['error'] =  $e->getMessage();
        header('location: /doneer');
        exit();
    }
});

// aanvragen
get('/aanvraag', 'views/aanvraag.php');
post('/aanvraag', function () {
    try {
        $controller = new NeedController();
        $controller->post();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('location: /aanvraag');
        exit();
    }
});

// admin pagina's
get('/admin', 'views/admin/adminList.php');
get('/admin/aanbiedingen', 'views/admin/offers.php');
get('/admin/aanvragen', 'views/admin/needs.php');
get('/admin/matches', 'views/admin/matches.php');

// TODO: verwijder hieronder
get('/admin/list', 'views/admin/allRows.php');

get('/formulier-donor', 'views/formulier-donor.php');
get('/formulier-need', 'views/formulier-need.php');
// error pagina's
any('/404', 'views/404.php');
