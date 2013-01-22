<?php

$news = $app['controllers_factory'];
$newsData = array(
    'news_id' => array('id'=> 'news_id', 'title'=> 'Title of the news')
);

$news->get('/', function() use ($app) {
    $sql = "SELECT * FROM news";
    $newsData = $app['db']->fetchAll($sql);
    $output = '<ul>';
    foreach ($newsData as $new) {
        $output .= '<li><a href="/news/'.$new['id'].'">'.$new['title'].'</a></li>';
    }
    $output .= '</ul>';
    return $output;
});

$news->get('/{id}', function(Silex\Application $app, $id) use ($newsData) {
    $sql = "SELECT * FROM news WHERE id = ?";
    $news = $app['db']->fetchAssoc($sql, array((int) $id));
    $output = '<h1>'.$news['title'].'</h1><p>'.$news['content'].'</p>';
    $views = (int)$news['views'];
    $sql = "UPDATE news SET views = ? WHERE id = ?";
    $app['db']->executeUpdate($sql, array(++$views, (int) $id));

    return $output;
});


$news->match('/post', function (Request $request) use ($app) {
    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('title')
        ->add('content')
        ->add('link')
        ->getForm();

    if ('POST' == $request->getMethod()) {
        $form->bind($request);
        if ($form->isValid()) {
            $data
        }
    }

});

$news->get('/post/confirm', function() use ($app) {
    return $app['twig']->render('news/confirm.twig');
});

return $news;


