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
    $files = $request->getUploadedFiles();
    $newfile = $files['attached'];
    $uploadFileName = $newfile->getClientFilename();
    if ($newfile->getError() === UPLOAD_ERR_OK) {
        $uploadFileName = $newfile->getClientFilename();
        //var_dump($uploadFileName);die();
        $filePath = "attached/$uploadFileName";
        $newfile->moveTo("attached/$uploadFileName");

    }
    $mapper = new \App\TaskMapper($this->db);
    $lastId = $mapper->addTask($data);
    $mapper->addttached($filePath,$lastId);
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

$app->get('/task/details', function (Request $request, Response $response) {
    $mapper = new \App\TaskMapper($this->db);
    //$task=$mapper->taskDetails();
    //var_dump($task); die();
    $response = $this->view->render($response, "task_details.twig");
    return $response;
})->add($mw);
