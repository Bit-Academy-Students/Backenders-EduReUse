<?php

use Controllers\DonateController;
use Controllers\NeedController;

require_once __DIR__ . '/router.php';

get('/', 'views/home.php');

get('/doneer', 'views/doneer.php');
post('/doneer', function () {
    try {
        // voeg donatie toe aan database
        new DonateController()->post();
    } catch (Exception $e) {
        $_SESSION['error'] =  $e->getMessage();
        header('location: /doneer');
        exit();
    }
});

get('/aanvraag', 'views/aanvraag.php');
post('/aanvraag', function () {
    try {
        new NeedController()->post();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('location: /aanvraag');
        exit();
    }
});

get('/admin', 'views/admin/adminPage.php');

any('/404', 'views/404.php');
