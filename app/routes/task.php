<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



$app->get('/task/create', function (Request $request, Response $response) {
    //var_dump(\App\Utility::test());
    //die();
    $mapper = new \App\TaskMapper($this->db);
    $mapper_member = new \App\MemberMapper($this->db);
    $member=$mapper_member->getUser();
    //var_dump($member); die();
    $msg = $this->flash->getMessages();
    return $this->view->render($response, 'task.twig',['mem'=>$member,'message'=>$msg]);
})->add($mw);

$app->post('/task/insert', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    //$map = new \App\TaskMapper($this->db);
    /*$files = $request->getUploadedFiles();
    $newfile = $files['attached'];
    $uploadFileName = $newfile->getClientFilename();
    if ($newfile->getError() === UPLOAD_ERR_OK) {
        $uploadFileName = $newfile->getClientFilename();
        //var_dump($uploadFileName);die();
        $filePath = "attached/$uploadFileName";
        $newfile->moveTo("attached/$uploadFileName");
    }*/
    $mapper = new \App\TaskMapper($this->db);

    $lastId = $mapper->addTask($data);
    //$mapper->addttached($filePath,$lastId);
    $this->flash->addMessage('success', 'Task is assigned!!!');
    //return $response->withRedirect('/task/attached/' . $lastId);
    return $response->withRedirect('/task/task_details/' . $lastId);
})->add($mw);

$app->get('/task/list[/{type}]', function (Request $request, Response $response) {
    //$dateType = $request->get('date_type');
    $queryParams = $request->getQueryParams();
    //var_dump($queryParams);
    //die();
    $dateType = $request->getAttribute('type');

    $mapper_member = new \App\MemberMapper($this->db);
    $member=$mapper_member->getUser();

    $mapper = new \App\TaskMapper($this->db);
    if ($dateType == 'today') {
        $typeTitle = 'TODAY';
        $task = $mapper->getTodayTask($queryParams);
    } elseif($dateType == 'all') {
        $typeTitle = 'ALL';
        $task=$mapper->getTask($queryParams);
        //var_dump($task); die();
    }elseif($dateType == 'complete'){
        $typeTitle = 'COMPLETE';
        $task=$mapper->getCompleteTask($queryParams);
    }elseif($dateType == 'pending'){
        $typeTitle = 'PENDING';
        $task=$mapper->getPendingTask($queryParams);
    }

    $update_message = $this->flash->getMessages();
    $response = $this->view->render($response, "tasklist.twig",['task'=>$task,'message'=>$update_message , 'typeTitle' => $typeTitle , 'mem'=>$member,]);
    return $response;
})->add($mw);

$app->get('/task/getCountComplete[/{type}]', function (Request $request, Response $response) {
    $dateType = $request->getAttribute('type');

    $mapper = new \App\TaskMapper($this->db);
    if ($dateType == 'todaytask') {
        $typeTitle = 'TODAYTASK';
        $task = $mapper->getCountToday();
    } elseif($dateType == 'alltask') {
        $typeTitle = 'ALLTASK';
        $task=$mapper->getCountTask();
        //var_dump($task); die();
    }elseif($dateType == 'completetask'){
        $typeTitle = 'COMPLETETASK';
        $task=$mapper->getCountComplete();
    }elseif($dateType == 'pendingtask'){
        $typeTitle = 'PENDINGTASK';
        $task=$mapper->getCountPending();
    }
    //$delete_message = $this->flash->getMessages();
    $response = $this->view->render($response, "tasklist.twig",['task'=>$task,'typeTitle' => $typeTitle]);
    //return $response;
})->add($mw);


$app->get('/task/members_task_list[/{type}]', function (Request $request, Response $response) {
    $dateType = $request->getAttribute('type');
    $mapper = new \App\TaskMapper($this->db);
    if($dateType == 'today'){
        $typeTitle = 'TODAY';

        $task=$mapper->memberTodayTask();
    }else{
        $typeTitle = 'All';
        $task=$mapper->memberAllTask();
        //var_dump($task); die();
    }

    $status_message = $this->flash->getMessages();
    $response = $this->view->render($response, "member_tasklist.twig",['task'=>$task, 'message'=>$status_message]);
    return $response;
})->add($mw);

$app->get('/task/task_details/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\TaskMapper($this->db);
    $details_data = $mapper->taskDetails($id);
    $att = $mapper->getAttacched($id);
    $create_message = $this->flash->getMessages();
    $all_comments = $mapper->getAllComments($id);
    $response = $this->view->render($response, "task_details.twig",['details'=>$details_data,'attached'=>$att,'message'=>$create_message,'all_comments'=>$all_comments]);
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
    header('Content-Length: ' . filesize($zipname));
    readfile($zipname);
})->add($mw);


$app->get('/task/task_update/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\TaskMapper($this->db);
    $mapper_member = new \App\MemberMapper($this->db);
    $member=$mapper_member->getMember();
    $update_data = $mapper->getTaskId($id);
    $response = $this->view->render($response, "task_update.twig",['update_data'=>$update_data,'member'=>$member]);
    return $response;
})->add($mw);

$app->post('/task/update', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    //var_dump($data); die();
    $mapper = new \App\TaskMapper($this->db);
    $sql=$mapper->editTask($data);
    //var_dump($sql); die();
    $this->flash->addMessage('success', 'Update! Successfuly Updated!!!');
    //$this->flash->addMessage('update_message', 'Successfuly updated !!!');
    return $response->withRedirect('/task/task_details/'.$sql);
});

$app->get('/task/task_delete/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\TaskMapper($this->db);
    $mapper->taskDelete($id);
    $this->flash->addMessage('error', 'Task is Deleted!!!');
    return $response->withRedirect('/task/list/all');
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


$app->post('/task/members_status', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    // var_dump($data); die();
    if (isset($data['task_id'][0])){
        $mapper = new \App\TaskMapper($this->db);
        $sql=$mapper->updateMemberStatus($data);
        $this->flash->addMessage('success', 'Update! Successfuly Updated!!!');
        return $response->withRedirect('/task/members_task_list/today');
    }else{
        $this->flash->addMessage('error', 'Please check in task!!!');
        return $response->withRedirect('/task/members_task_list/today');
    }

})->add($mw);


$app->post('/task/admin_status', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    //var_dump($data['task_id'][0]);die();
    if (isset($data['task_id'][0])){
        $mapper = new \App\TaskMapper($this->db);
        $id=$mapper->updateAdminStatus($data);
        $this->flash->addMessage('success', 'Update! Successfuly Updated!!!');
        return $response->withRedirect('/task/task_details');
    }else{
        $this->flash->addMessage('error', 'Please check in task!!!');
        return $response->withRedirect('/task/list/today');
    }

})->add($mw);

$app->post('/task/taska_status', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    //var_dump($data); die();
    $mapper = new \App\TaskMapper($this->db);
    $sql=$mapper->updateTaskStatus($data);
    //var_dump($sql); die();
    $this->flash->addMessage('update_message', 'Update! Successfuly Updated!!!');
    return $response->withRedirect('/task/list/today');
})->add($mw);

$app->post('/task/taskm_status', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    //var_dump($data); die();
    $mapper = new \App\TaskMapper($this->db);
    $sql=$mapper->updateMemTaskStatus($data);
    //var_dump($sql); die();
    $this->flash->addMessage('update_message', 'Update! Successfuly Updated!!!');
    return $response->withRedirect('/task/members_task_list/today');
})->add($mw);

$app->post('/comment', function (Request $request, Response $response){
    $data = $request->getParsedBody();
    $mapper = new \App\TaskMapper($this->db);
    $mapper->InsertComment($data);
    return $response->withRedirect('/task/task_details/'.$data['task_id']);
})->add($mw);

$app->get('/task/profile', function (Request $request, Response $response) {
    //var_dump(\App\Utility::test());
    //die();
    $mapper = new \App\TaskMapper($this->db);
    $mapper_member = new \App\MemberMapper($this->db);
    $msg = $this->flash->getMessages();
    return $this->view->render($response, 'admin_profile.twig',['message'=>$msg]);
})->add($mw);

$app->post('/task/profile_update', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    //var_dump($data); die();
    //var_dump($data); die();
    $mapper = new \App\TaskMapper($this->db);
    $sql=$mapper->profileUpdate($data);
    //var_dump($sql); die();
    $this->flash->addMessage('success', 'Update! Successfuly Updated!!!');
    //$this->flash->addMessage('update_message', 'Successfuly updated !!!');
    return $response->withRedirect('/task/profile');
});

