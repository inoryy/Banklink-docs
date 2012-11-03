<?php

require_once __DIR__.'/../app/kernel.php';

$app->get('/{_locale}', function ($_locale) use ($app) {
    return $app['twig']->render('homepage.html.twig', array(
    ));
})->value('_locale', 'et')->bind('homepage');

$app->run();