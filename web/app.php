<?php

require_once __DIR__.'/../app/kernel.php';

 // change default locale to 'et' when translations fully implemented
$app->get('/{_locale}', function () use ($app) {
    $swedbankRequest = $app['swedbank']->preparePaymentRequest(12345, 25, $message = 'Beer + Movie');
    
    return $app['twig']->render('homepage.html.twig', array(
        'swedbank' => $swedbankRequest
    ));
})->value('_locale', 'en')->bind('homepage');

$app->run();