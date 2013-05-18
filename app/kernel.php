<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

// Twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../app/views',
    'twig.options' => array('cache' => __DIR__.'/../app/cache'),
));

// Translations
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallback' => 'en', // switch to 'et' when fully implemented
));
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new \Symfony\Component\Translation\Loader\YamlFileLoader());

    $translator->addResource('yaml', __DIR__.'/../app/translations/en.yml', 'en');
    $translator->addResource('yaml', __DIR__.'/../app/translations/et.yml', 'et');
    $translator->addResource('yaml', __DIR__.'/../app/translations/ru.yml', 'ru');

    return $translator;
}));

// Routing in templates
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// Prepare banklinks
$app['swedbank'] = $app->share(function () use($app) {
    $protocol = new \Banklink\Protocol\SwedbankiPizza(
        'uid401120',
        'Banklink',
        '1199331133112',
        __DIR__.'/data/swedbank/private_key.pem',
        __DIR__.'/data/swedbank/public_key.pem',
        $app['url_generator']->generate('payment_callback', array(
            'bank' => 'swedbank'
        ), true)
    );

    return new \Banklink\Swedbank($protocol, false, 'https://pangalink.net/banklink/swedbank');
});

$app['lhv'] = $app->share(function () use($app) {
    $protocol = new \Banklink\Protocol\iPizza(
        'uid274056',
        'Banklink',
        '1199331133121',
        __DIR__.'/data/lhv/private_key.pem',
        __DIR__.'/data/lhv/public_key.pem',
        $app['url_generator']->generate('payment_callback', array(
            'bank' => 'lhv'
        ), true)
    );

    return new \Banklink\LHV($protocol, true);
});

$app['seb'] = $app->share(function () use($app) {
    $protocol = new \Banklink\Protocol\iPizza(
        'uid401133',
        'Banklink SEB',
        '119933113',
        __DIR__.'/data/seb/private_key.pem',
        __DIR__.'/data/seb/public_key.pem',
        $app['url_generator']->generate('payment_callback', array(
            'bank' => 'seb'
        ), true)
    );

    return new \Banklink\SEB($protocol, false, 'https://pangalink.net/banklink/seb');
});

// additional test enviroment to actual SEB servers
$app['seb_test'] = $app->share(function () use($app) {
    $protocol = new \Banklink\Protocol\iPizza(
        'testvpos',
        'Banklink SEB',
        '10002050618003',
        __DIR__.'/data/seb-test/private_key.pem',
        __DIR__.'/data/seb-test/public_key.pem',
        $app['url_generator']->generate('payment_callback', array(
            'bank' => 'seb_test'
        ), true)
    );

    return new \Banklink\SEB($protocol, false, 'https://www.seb.ee/cgi-bin/dv.sh/un3min.r');
});

$app['danskebank'] = $app->share(function () use($app) {
    $protocol = new \Banklink\Protocol\iPizza(
        'uid274108',
        'Banklink Sampo',
        '11993333113',
        __DIR__.'/data/sampo/private_key.pem',
        __DIR__.'/data/sampo/public_key.pem',
        $app['url_generator']->generate('payment_callback', array(
            'bank' => 'danskebank'
        ), true)
    );

    return new \Banklink\DanskeBank($protocol, true);
});

$app['krediidipank'] = $app->share(function () use($app) {
    $protocol = new \Banklink\Protocol\iPizza(
        'uid274124',
        'Banklink Krediidipank',
        '1122333113',
        __DIR__.'/data/krediidipank/private_key.pem',
        __DIR__.'/data/krediidipank/public_key.pem',
        $app['url_generator']->generate('payment_callback', array(
            'bank' => 'krediidipank'
        ), true)
    );

    return new \Banklink\Krediidipank($protocol, true);
});

$app['nordea'] = $app->share(function () use($app) {
    $protocol = new \Banklink\Protocol\Solo(
        '10274577',
        'iC1pmFo2WkrH5bw2WXTzE5JhAaWCpDbi',
        $app['url_generator']->generate('payment_callback', array(
            'bank' => 'nordea'
        ), true)
    );

    return new \Banklink\Nordea($protocol, true);
});