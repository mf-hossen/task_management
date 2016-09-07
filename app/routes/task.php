<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



$app->get('/task/create', function (Request $request, Response $response) {
   //var_dump(\App\Utility::test());
    //die();
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
    return $response->withRedirect('/task/list');
})->add($mw);

$app->get('/task/list', function (Request $request, Response $response) {
    $mapper = new \App\TaskMapper($this->db);
    $task=$mapper->getTask();
    //var_dump($task); die();
    $response = $this->view->render($response, "tasklist.twig",['task'=>$task]);
    return $response;
})->add($mw);

$app->get('/task/memlist', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $mapper = new \App\TaskMapper($this->db);
    $task=$mapper->memberTaskList($data);
    //var_dump($task); die();
    $response = $this->view->render($response, "tasklist.twig",['task'=>$task]);
    return $response;
})->add($mw);


$app->get('/task/task_details/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\TaskMapper($this->db);
    $details_data = $mapper->taskDetails($id);
    //var_dump($details_data); die();
    $response = $this->view->render($response, "task_details.twig",['details'=>$details_data]);
    return $response;
});