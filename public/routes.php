<?php

use Controllers\DonateController;

require_once __DIR__ . '/router.php';

// homepage
get('/', 'views/homepage.php');

// login/register/logout
get('/login', 'views/login.php');
post('/login', 'views/login.php');
get('/logout', 'views/logout.php');
get('/register', 'views/register.php');
post('/register', 'views/register.php');

// user's posts
get('/school-posts', 'views/schoolPosts.php');

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
post('/aanvraag', 'views/aanvraag.php');

// admin pagina's
get('/admin/alles', 'views/admin/adminList.php');
get('/admin/aanbiedingen', 'views/admin/offers.php');
get('/admin/aanvragen', 'views/admin/needs.php');
get('/admin/matches', 'views/admin/matches.php');
get('/admin/ready-to-match/$needId/$typeLabel', 'views/admin/readyToMatch.php');
post('/admin/match', 'views/admin/officialMatch.php');
if ($_SERVER['REQUEST_URI'] === '/admin/ready-to-match' || $_SERVER['REQUEST_URI'] === '/admin/ready-to-match/') {
    header('location: /admin/aanvragen');
    exit();
}
if ($_SERVER['REQUEST_URI'] === '/admin') {
    header('location: /admin/alles');
    exit();
}

// TODO: DELETE ROUTE
// post('/admin/matches', 'views/admin/matches.php');

// jenebi aanbod
get('/aanbod', 'views/aanbod.php');

// error pagina's
any('/404', 'views/404.php');
