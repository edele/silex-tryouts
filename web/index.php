<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->get('/', function() {
    return "<a href='/blog'>blog</a><br><br>"
    	."Send me a message: <form method='post' action='/feedback'><input name='message' type='text'><input type='submit'></form>";
});

$app->get('/hello', function() {
    return 'Hello!';
});

$blogPosts = array(
    1 => array(
        'date'      => '2011-03-29',
        'author'    => 'igorw',
        'title'     => 'Using Silex',
        'body'      => 'Visiting /blog will return a list of blog post titles. The use statement means something different in this context. It tells the closure to import the $blogPosts variable from the outer scope. This allows you to use it from within the closure.',
    ),
    2 => array(
        'date'      => '2011-03-29',
        'author'    => 'edele',
        'title'     => 'But does it float?',
        'body'      => '<img src="http://payload275.cargocollective.com/1/0/128/7818950/10_Muzika03_905.jpg">',
    ),
);

$app->get('/blog', function () use ($blogPosts) {
    $output = '';
    foreach ($blogPosts as $i => $post) {
        $output .= "<a href='/blog/".$i."'>".$post["title"]."</a>";
        $output .= '<br />';
    }

    return $output;
});

$app->get('/blog/{id}', function (Silex\Application $app, $id) use ($blogPosts) {
    if (!isset($blogPosts[$id])) {
        $app->abort(404, "Post $id does not exist.");
    }

    $post = $blogPosts[$id];

    return  "<h1>{$post['title']}</h1>".
            "<p>{$post['body']}</p>";
});

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->post('/feedback', function (Request $request) {
    $message = $request->get('message');
    mail('igrnd0@gmail.com', 'Pipirka Feedback', $message);

    return new Response('You sent: <i>'.$message.'</i><br>Thank you for your feedback!', 201);
});

$app->run();