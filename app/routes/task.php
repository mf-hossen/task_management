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
    $msg = $this->flash->getMessages();
    return $this->view->render($response, 'task.twig',['mem'=>$member,'message'=>$msg]);
})->add($mw);

$app->post('/task/insert', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $map = new \App\TaskMapper($this->db);
    $check_id = $map->checkClientId($data);

    if($check_id==true){
        $this->flash->addMessage('error', 'Client ID Already exists');
        return $response->withStatus(302)->withHeader('Location', '/task/create');
    }

    $mapper = new \App\TaskMapper($this->db);
    $lastId = $mapper->addTask($data);
    $this->flash->addMessage('success', 'Task is assigned!!!');
    return $response->withRedirect('/task/attached/'.$lastId);
})->add($mw);

$app->get('/task/list[/{type}]', function (Request $request, Response $response) {
    //$dateType = $request->get('date_type');
    $dateType = $request->getAttribute('type');

    $mapper = new \App\TaskMapper($this->db);
    if($dateType == 'today'){
        $typeTitle = 'TODAY';
        $task=$mapper->getTodayTask();
    }else{
        $typeTitle = 'All';
        $task=$mapper->getTask();
        //var_dump($task); die();
    }
    $delete_message = $this->flash->getMessages();
    $response = $this->view->render($response, "tasklist.twig",['task'=>$task,'message'=>$delete_message , 'typeTitle' => $typeTitle]);
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
    $response = $this->view->render($response, "task_details.twig",['details'=>$details_data,'attached'=>$att,'message'=>$create_message]);
    return $response;
})->add($mw);

$app->get('/attach-zip/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\TaskMapper($this->db);
    $att = $mapper->getAttacched($id);


    //............
//var_dump($att);die();
    $files = $att;
    $rnd = rand(0,10000);
    $zipname = $rnd.'-attach.zip';
    $zip = new ZipArchive;
    $zip->open($zipname, ZipArchive::CREATE);
    foreach ($files as $file) {
        $zip->addFile($file['attached_path']);
    }
    $zip->close();
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename='.$zipname);
    //header('Content-Length: ' . filesize($zipname));
    readfile($zipname);

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
    $this->flash->addMessage('error', 'Task is Deleted!!!');
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