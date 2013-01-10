<?php
// web/index.php

require_once  __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->get('/', function() use ($app) {
    return 'Welcome to my new website';
});

$news = array(
    'news_id' => array('id'=> 'news_id', 'title'=> 'Title of the news')
);

$app->get('/news', function() use ($news) {
    $output = '';
    foreach ($news as $new) {
        $output .= '<a href="/news/'.$new['id'].'">'.$new['title'].'</a>';
    }
    return $output;
});

$app->get('/news/{id}', function(Silex\Application $app, $id) use ($news) {
    if (!isset ($news[$id])) {
        $app->abort(404, 'Post '.$id.' not found');
    }
    
    $new = $news[$id];
    
    $output = '<h1>'.$new['title'].'</h1>';
    return $output;
});

$app->get('/hello/{name}', function($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app->run();
