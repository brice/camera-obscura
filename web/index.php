<?php
// web/index.php

require_once  __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\FormServiceProvider;

$app = new Silex\Application();

// Register Twig service provider
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
// Register form service provider
$app->register(new FormServiceProvider());

// Set to debug  for now
$app['debug'] = true;

// Temporary config file
$config = array(
    'email' => 'Your email'
);

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'test',
        'user'      => 'root',
        'password'  => 'password',
        'charset'   => 'utf8',
    ),
));


// Home page of the website
$app->get('/', function() use ($app) {
    return $app['twig']->render('home.twig');
});

$app->mount('/news', include 'news.php');

$app->get('/contact', function() use($app) {
    $output = '<form method="post" action="/contact"><label for="message">Send me a message</label><input type="text" name="message" id="message"><input type="submit"></form>';
    return $output;
});


$app->post('/contact', function(Request $request) use($config) {
    $message = $request->get('message');
    mail($config['email'], 'Message from your site', $message);
    return new Response('Thank you for your message!', 201);
});

$app->run();
