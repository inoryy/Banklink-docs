<?php

require_once __DIR__.'/../app/kernel.php';

$app->get('/{_locale}', function ($_locale) use ($app) {
    $swedbankRequest = $app['swedbank']->preparePaymentRequest(12345, 25, $message = 'Beer + Movie');

    return $app['twig']->render('homepage.html.twig', array(
        'swedbank' => $swedbankRequest
    ));
})->value('_locale', 'en') // change default locale to 'et' when translations fully implemented
  ->bind('homepage')
;

$app->run();