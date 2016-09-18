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
    $mapper = new \App\TaskMapper($this->db);
    $lastId = $mapper->addTask($data);
    $this->flash->addMessage('create_message', 'Task is assigned!!!');
    return $response->withRedirect('/task/attached/'.$lastId);
})->add($mw);

$app->get('/task/list[/{type}]', function (Request $request, Response $response) {
    //$dateType = $request->get('date_type');
    $dateType = $request->getAttribute('type');

    $mapper = new \App\TaskMapper($this->db);
    if($dateType == 'today'){
        $task=$mapper->getTodayTask();
    }else{

        $task=$mapper->getTask();
    }
    $delete_message = $this->flash->getMessages();
    $response = $this->view->render($response, "tasklist.twig",['task'=>$task,'del_msg'=>$delete_message]);
    return $response;
})->add($mw);


$app->get('/task/members_task_list', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $mapper = new \App\TaskMapper($this->db);
    $task=$mapper->memberAllTask($data);
    //var_dump($task); die();
    $response = $this->view->render($response, "member_tasklist.twig",['task'=>$task]);
    return $response;
})->add($mw);


$app->get('/task/task_details/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\TaskMapper($this->db);
    $details_data = $mapper->taskDetails($id);
    //var_dump($details_data); die();
    $att = $mapper->getAttacched($id);
    //var_dump($att); die();
    $create_message = $this->flash->getMessages();
    $response = $this->view->render($response, "task_details.twig",['details'=>$details_data,'attached'=>$att,'cre_message'=>$create_message]);
    return $response;
})->add($mw);

$app->get('/task/task_update/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\TaskMapper($this->db);
    $mapper_member = new \App\MemberMapper($this->db);
    $member=$mapper_member->getMember();
    $update_data = $mapper->getTaskId($id);
    //$client_id=$mapper->getClientId();
    //var_dump($client_id); die();
    $response = $this->view->render($response, "task_update.twig",['update_data'=>$update_data,'member'=>$member]);
    return $response;
})->add($mw);

$app->get('/task/task_delete/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\TaskMapper($this->db);
    $mapper->taskDelete($id);
    $this->flash->addMessage('delete_message', 'Task is Deleted!!!');
    return $response->withRedirect('/task/list');
})->add($mw);

$app->get('/task/attached/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\TaskMapper($this->db);
    $details_data = $mapper->taskDetails($id);
    $response = $this->view->render($response, "attach.twig",['details'=>$details_data]);
    return $response;
})->add($mw);

$app->post('/upload/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $files = $request->getUploadedFiles();
    $newfile = $files['file'];
    $rnd =  rand(1,100000);
    if ($newfile->getError() === UPLOAD_ERR_OK) {
        $uploadFileName = $newfile->getClientFilename();
        $filePath = "attached/$rnd.$uploadFileName";
        $newfile->moveTo($filePath);
        $mapper = new \App\TaskMapper($this->db);
        $mapper->addAttached($filePath,$id);

    }

})->add($mw);