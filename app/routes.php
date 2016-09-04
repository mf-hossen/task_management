<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response) {

    //var_dump(R::dispense('tasks')); die();
   // return $this->view->render($response, 'layout.twig');
    return $this->view->render($response, 'welcome.twig');
});

$app->get('/task', function (Request $request, Response $response) {

    //var_dump(R::dispense('tasks')); die();
    //return $this->view->render($response, 'layout.twig');
    return $this->view->render($response, 'task.twig');
});








