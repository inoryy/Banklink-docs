<?php

require_once __DIR__.'/../app/kernel.php';

 // change default locale to 'et' when translations fully implemented
$app->get('/{_locale}', function () use ($app) {
    $lhvRequest          = $app['lhv']->preparePaymentRequest(12345, 25, $message = 'Beer + Movie');
    $sebRequest          = $app['seb']->preparePaymentRequest(12345, 25, $message = 'Beer + Movie');
    $danskebankRequest   = $app['danskebank']->preparePaymentRequest(12345, 25, $message = 'Beer + Movie');
    $sebTestRequest      = $app['seb_test']->preparePaymentRequest(12345, 25, $message = 'Testime ');
    $swedbankRequest     = $app['swedbank']->preparePaymentRequest(12345, 25, $message = 'Beer + Movie');
    $krediidipankRequest = $app['krediidipank']->preparePaymentRequest(12345, 25, $message = 'Beer + Movie');
    $nordeaRequest       = $app['nordea']->preparePaymentRequest(12345, 25, $message = 'Beer + Movie');

    return $app['twig']->render('homepage.html.twig', array(
        'seb'      => $sebRequest,
        'seb_test' => $sebTestRequest,
        'lhv'      => $lhvRequest,
        'sampo'    => $sampoRequest,
        'swedbank' => $swedbankRequest,
        'krediidipank' => $krediidipankRequest,
        'nordea'       => $nordeaRequest,
    ));
})->value('_locale', 'en')->bind('homepage');

$app->match('/{_locale}/payment-callback/{bank}', function($bank) use ($app) {
    if (!isset($app[$bank])) {
        throw new \InvalidArgumentException(sprintf('This bank type (%s) is not supported', $bank));
    }

    $requestData = array_merge(
        $app['request']->query->all(),  // Nordea sends back data via GET
        $app['request']->request->all() // but thankfully most banks send via POST
    );
    $paymentResponse = $app[$bank]->handleResponse($requestData);

    return $app['twig']->render('payment_callback.html.twig', array(
        'response' => $paymentResponse
    ));
})->method('GET|POST')->bind('payment_callback');

$app->run();