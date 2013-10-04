<?php

//namespace App;

require_once __DIR__.'/bootstrap.php';

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


# Routes 

#View
$app->get('/', function() use($app) {
  return $app['twig']->render('index.twig', array(
    'path' => 'home',
  ));
});


#REST
$app->get('/rest', function() use($app) {
     // retorno em json
    return $app->json($data=array('start'=>'This is my world!',
                                  'books'=> array('4hWW','JS Patterns','NoSQL Essentials')
                                 ), $status=200);
});

#view sample
$app->get('/{path}', function($path) use($app) {
  return $app['twig']->render('index.twig', array(
    'path' => $path,
  ));
});


//var_dump('app',$app); exit;

return $app;