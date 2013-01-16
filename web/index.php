<?php
// web/index.php

require_once  __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

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


$app->get('/', function() use ($app) {
    return 'Welcome to my new website';
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
