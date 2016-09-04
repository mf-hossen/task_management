<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



$app->get('/task', function (Request $request, Response $response) {
    return $this->view->render($response, 'task.twig');
});

$app->post('/insert', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    //var_dump($data);die();
    $mapper = new \App\TaskMapper($this->db);
    $task=$mapper->addTask($data);
    var_dump($task);die();


});
