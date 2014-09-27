<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem;

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

include("blog.php");

$app->post('/feedback', function (Request $request) {
    $message = $request->get('message');
    mail('igrnd0@gmail.com', 'Pipirka Feedback', $message);

    return new Response('You sent: <i>'.$message.'</i><br>Thank you for your feedback!', 201);
});

$app->error(function (\LogicException $e, $code) {
    // this handler will only handle \LogicException exceptions
    // and exceptions that extends \LogicException
    return new Response("Logic Error!");
});


$app->error(function (\Exception $e, $code) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }
    return new Response($message);
});


$app->get('/copy/{originFile}/{targetFile}', function (Silex\Application $app, Filesystem $fs, $originFile, $targetFile) {
    /*if (!isset($blogPosts[$id])) {
        $app->abort(404, "Post $id does not exist.");
    }*/

    try {
    	$fs->copy($originFile, $targetFile);
    } catch (Exception $e) {
    	print_r($e);
    	$app->abort(404, "Error copying file");
    }

    return  "Copied $originFile to $targetFile!";
});

$app->get('/copy', function () {
    return  "You can copy files";
});

$app->get('/name', function (Silex\Application $app) {
    $name = $app['request']->get('name');
    return "You provided the name {$app->escape($name)}.";
});

$app->get('/name.json', function (Silex\Application $app) {
    $name = $app['request']->get('name');
    return $app->json(array('name' => $name));
});

$app->run();