<?php 

$blog = $app['controllers_factory'];

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

$blog->get('/', function () use ($blogPosts) {
    $output = '';
    foreach ($blogPosts as $i => $post) {
        $output .= "<a href='/blog/".$i."'>".$post["title"]."</a>";
        $output .= '<br />';
    }

    return $output;
});

$blog->get('/{id}', function (Silex\Application $app, $id) use ($blogPosts) {
    if (!isset($blogPosts[$id])) {
        $app->abort(404, "Post $id does not exist.");
    }

    $post = $blogPosts[$id];

    return  "<h1>{$post['title']}</h1>".
            "<p>{$post['body']}</p>";
});

$app->mount('/blog', $blog);