<?php

include __DIR__ . '/config/cfg.php';
require_once __DIR__ . '/autoload.php';

$helpers = new \Helpers\AuthHelper();

$client = new GuzzleHttp\Client();

$tokens = $helpers->getTokens();

if (!isset($tokens['access_token']) && !isset($_GET['code'])) {
    $helpers->redirectToLogin();
}

if (!empty($_GET['code'])) {
    $result = $helpers->getTokensFromCode($_GET['code']);

    $helpers->saveTokens($result);
    header('Location: ' . REDIRECT_URL);
    exit;
}

die('Application is authorized. You can use the cli in order to manage calendar events');

