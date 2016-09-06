<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



$app->get('/task/create', function (Request $request, Response $response) {
    $mapper = new \App\TaskMapper($this->db);
    $mapper_dept = new \App\MemberMapper($this->db);
    $member=$mapper_dept->getMember();
    //var_dump($member); die();
    return $this->view->render($response, 'task.twig',['mem'=>$member]);
})->add($mw);

$app->post('/task/insert', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    //var_dump($data);die();
    $mapper = new \App\TaskMapper($this->db);
    $mapper->addTask($data);
    //var_dump($task);die();
    return $response->withRedirect('/create');
})->add($mw);

$app->get('/task/list', function (Request $request, Response $response) {
    $mapper = new \App\TaskMapper($this->db);
    $task=$mapper->getTask();
    //var_dump($task); die();
    $response = $this->view->render($response, "tasklist.twig",['task'=>$task]);
    return $response;
})->add($mw);
