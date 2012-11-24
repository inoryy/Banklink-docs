<?php

require_once __DIR__.'/../app/kernel.php';

 // change default locale to 'et' when translations fully implemented
$app->get('/{_locale}', function () use ($app) {
    $swedbankRequest = $app['swedbank']->preparePaymentRequest(12345, 25, $message = 'Beer + Movie');

    return $app['twig']->render('homepage.html.twig', array(
        'swedbank' => $swedbankRequest
    ));
})->value('_locale', 'en')->bind('homepage');

$app->post('/{_locale}/payment-callback/{bank}', function($bank) use ($app) {
    if (!isset($app[$bank])) {
        throw new \InvalidArgumentException(sprintf('This bank type (%s) is not supported', $bank));
    }

    $paymentResponse = $app[$bank]->handleResponse($app['request']->request->all());

    return $app['twig']->render('payment_callback.html.twig', array(
        'response' => $paymentResponse
    ));
})->bind('payment_callback');

$app->run();