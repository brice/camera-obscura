<?php
// web/index.php

require_once  __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\FormServiceProvider;

$app = new Silex\Application();

// Register Twig service provider

// Setup multiple path depends on Website URL
$path = array(__DIR__.'/templates');
if (file_exists(__DIR.'/templates/sites/'.$_REQUEST['host'])); {
   $path[] ==  __DIR.'/templates/sites/'.$_REQUEST['host'];
}

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => $path,
));

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
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

$app->mount('/portfolio', include 'portfolio.php');

$app->mount('/admin', include 'admin.php');

// Display signin cwand login form
$app->get('/signin', function() {
    return $app['twig']->render('signin.twig');
});

// Create an account
$app->post('/signup', function() { });

// Log user
$app->post('/login', function() { });

$app->get('/contact', function() use($app) {
    return $app['twig']->render('contact.twig');
});

$app->post('/contact', function(Request $request) use($config) {
    $message = $request->get('message');
    mail($config['email'], 'Message from your site', $message);
    return new Response('Thank you for your message!', 201);
});

return $app;
