<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response) {

    //var_dump(R::dispense('tasks')); die();
   // return $this->view->render($response, 'layout.twig');
    return $this->view->render($response, 'welcome.twig');
});

$app->get('/login', function (Request $request, Response $response) {

    //var_dump(R::dispense('tasks')); die();
    // return $this->view->render($response, 'layout.twig');
    $mapper = new \App\UserMapper($this->db);
    var_dump($mapper->user());die();
    return $this->view->render($response, 'login.twig');
});

$app->get('/task', function (Request $request, Response $response) {

    //var_dump(R::dispense('tasks')); die();
    //return $this->view->render($response, 'layout.twig');
    return $this->view->render($response, 'task.twig');
});








