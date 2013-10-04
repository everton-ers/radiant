<?php

#
# Radiant
# The micro-framework based on Silex
# @author everton-ers
#

$loader = require __DIR__.'/../vendor/autoload.php';


use Symfony\Component\ClassLoader\DebugClassLoader;
use Symfony\Component\HttpKernel\Debug\ErrorHandler;
use Symfony\Component\HttpKernel\Debug\ExceptionHandler;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

error_reporting(-1);
DebugClassLoader::enable();
ErrorHandler::register();
if ('cli' !== php_sapi_name()) {
    ExceptionHandler::register();
}

$loader->add('App', __DIR__.'/../app');

$app = new Silex\Application();


//$app->register(new Silex\Extension\TwigExtension(), array(
//    'twig.path' => __DIR__.'/../views',
//    'twig.class_path' => __DIR__.'/../vendor/twig/lib',
//));

## configure view
$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/views',
  'twig.class_path' => __DIR__.'/../vendor/twig/twig/lib',
));

# error page
$app->error(function (\Exception $e) use ($app) {
    if ($e instanceof NotFoundHttpException) {
        return $app['twig']->render('error.twig', array(
            'code' => 404,
            'message' => 'The requested page could not be found.',
        ));
    }

    $code = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
    return $app['twig']->render('error.twig', array(
        'code' => $code,
        'message' => $e,
    ));
});
