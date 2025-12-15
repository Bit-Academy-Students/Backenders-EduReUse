<?php

use Controllers\DonateController;
use Controllers\MatchController;
use Controllers\UserController;

require_once __DIR__ . '/router.php';

// homepage
get('/', 'views/homepage.php');

// login/register/logout
get('/login', 'views/user/login.php');
post('/login', 'views/user/login.php');
get('/logout', 'views/user/logout.php');
get('/register', 'views/user/register.php');
post('/register', 'views/user/register.php');

// user + posts
get('/user/profiel', 'views/user/profiel.php');
get('/user/posts', 'views/user/posts.php');
get('/user/delete-offer', 'views/user/deleteOffer.php');
get('/user/detail-offer', 'views/user/detailOffer.php');
get('/user/detail-need', 'views/user/detailAanvraag.php');
get('/user/edit-profile', 'views/user/editUser.php');
post('/user/edit-profile', function () {
    try {
        $controller = new UserController();
        $controller->editUser();
    } catch (Exception $e) {
        $_SESSION['error'] =  $e->getMessage();
        header('location: /user/edit-profile');
        exit();
    }
});

get('/user/change-password', 'views/user/editPassword.php');
post('/user/change-password', function () {
    try {
        $controller = new UserController();
        $controller->editPass();
    } catch (Exception $e) {
        $_SESSION['error'] =  $e->getMessage();
        header('location: /user/change-password');
        exit();
    }
});
if ($_SERVER['REQUEST_URI'] === '/user') {
    header('location: /user/profiel');
    exit();
}

// donaties
get('/doneer', 'views/user/doneer-formulier.php');
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
get('/aanvraag', 'views/user/aanvraag-formulier.php');
post('/aanvraag', 'views/user/aanvraag-formulier.php');

// admin pagina's
get('/admin/need/$needId', 'views/admin/adminNeedDetail.php');
get('/admin/offer/$offerId', 'views/admin/adminOfferDetail.php');
get('/admin/alles', 'views/admin/adminList.php');
get('/admin/aanbiedingen', 'views/admin/offers.php');
get('/admin/aanvragen', 'views/admin/needs.php');
get('/admin/matches', 'views/admin/matches.php');
get('/admin/ready-to-match/$needId/$typeLabel', 'views/admin/readyToMatch.php');
get('/admin/match', 'views/admin/officialMatch.php');
post('/admin/match', function () {
    $controller = new MatchController();
    $controller->post($_POST);
});

if ($_SERVER['REQUEST_URI'] === '/admin/ready-to-match' || $_SERVER['REQUEST_URI'] === '/admin/ready-to-match/') {
    header('location: /admin/aanvragen');
    exit();
}
if ($_SERVER['REQUEST_URI'] === '/admin') {
    header('location: /admin/alles');
    exit();
}

// error pagina's
any('/404', 'views/404.php');
