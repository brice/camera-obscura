<?php

$news = $app['controllers_factory'];
$newsData = array(
    'news_id' => array('id'=> 'news_id', 'title'=> 'Title of the news')
);

$news->get('/', function() use ($newsData) {
    $output = '';
    foreach ($newsData as $new) {
        $output .= '<a href="/news/'.$new['id'].'">'.$new['title'].'</a>';
    }
    return $output;
});

$news->get('/{id}', function(Silex\Application $app, $id) use ($newsData) {
    if (!isset ($newsData[$id])) {
        $app->abort(404, 'Post '.$id.' not found');
    }
    
    $new = $newsData[$id];
    
    $output = '<h1>'.$new['title'].'</h1>';
    return $output;
});

return $news;


