<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



$app->get('/task/create', function (Request $request, Response $response) {
   //var_dump(\App\Utility::test());
    //die();
    $mapper = new \App\TaskMapper($this->db);
    $mapper_member = new \App\MemberMapper($this->db);
    $member=$mapper_member->getMember();
    //var_dump($member); die();
    return $this->view->render($response, 'task.twig',['mem'=>$member]);
})->add($mw);

$app->post('/task/insert', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $files = $request->getUploadedFiles();
    $newfile = $files['attached'];
    //var_dump($newfile);die();
    $uploadFileName = $newfile->getClientFilename();
    $rnd =  rand(1,100000);
    if ($newfile->getError() === UPLOAD_ERR_OK) {
        $uploadFileName = $newfile->getClientFilename();
        $filePath = "attached/$rnd.$uploadFileName";
        $newfile->moveTo($filePath);

    }
    $mapper = new \App\TaskMapper($this->db);
    $lastId = $mapper->addTask($data);
    $mapper->addAttached($filePath,$lastId);
    $this->flash->addMessage('create_message', 'Task is assigned!!!');
    return $response->withRedirect('/task/task_details/'.$lastId);
})->add($mw);

$app->get('/task/list', function (Request $request, Response $response) {
    $mapper = new \App\TaskMapper($this->db);
    $task=$mapper->getTask();
    //var_dump($task); die();
    $delete_message = $this->flash->getMessages();
    $response = $this->view->render($response, "tasklist.twig",['task'=>$task,'del_msg'=>$delete_message]);
    return $response;
})->add($mw);

$app->get('/task/memlist', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $mapper = new \App\TaskMapper($this->db);
    $task=$mapper->memberTaskList($data);
    //var_dump($task); die();
    $response = $this->view->render($response, "member_tasklist.twig",['task'=>$task]);
    return $response;
})->add($mw);


$app->get('/task/task_details/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\TaskMapper($this->db);
    $details_data = $mapper->taskDetails($id);
    $att = $mapper->getAttacched($id);
    $create_message = $this->flash->getMessages();
    $response = $this->view->render($response, "task_details.twig",['details'=>$details_data,'att'=>$att,'cre_message'=>$create_message]);
    return $response;
});

$app->get('/task/task_update/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\TaskMapper($this->db);
    $mapper_member = new \App\MemberMapper($this->db);
    $member=$mapper_member->getMember();
    $update_data = $mapper->getTaskId($id);
    $response = $this->view->render($response, "task_update.twig",['update_data'=>$update_data,'member'=>$member]);
    return $response;
});

$app->get('/task/task_delete/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\TaskMapper($this->db);
    $mapper->taskDelete($id);
    $this->flash->addMessage('delete_message', 'Task is Deleted!!!');
    return $response->withRedirect('/task/list');
});