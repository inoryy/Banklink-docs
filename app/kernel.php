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