<?php
include_once('config.php');

use Mysrc\classes\FbConfig;
use Mysrc\classes\GlobalsHandler;
use Mysrc\classes\FbDataLoader;
use Mysrc\controllers\LoginController;
use Mysrc\controllers\DataController;


$app = new Silex\Application();
$app['debug'] = true;
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/view',
));

$app->get('/', function () use ($app) {
    $contr = new LoginController(new FbConfig(new GlobalsHandler));
    return '';
    // return 'Hello '.$app->escape($name);
});

$app->get('/facebook', function () use ($app) {

    $contr = new DataController(new FbDataLoader(new GlobalsHandler()));
    $fbUserProfile = $contr->getFbProfile();
    return $app['twig']->render('profile.twig',
        ['data' => $fbUserProfile]);
    // return 'Hello '.$app->escape($name);
});

// $app->get('/hello/{name}', function ($name) use ($app) {
//     return $app['twig']->render('hello.twig', array(
//         'name' => $name,
//     ));
// });



$app->run();
