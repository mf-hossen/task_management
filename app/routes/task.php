<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->group('/task', function () use ($mwAdmin){

    /**
     * Create task
     */
    $this->get('/create', function (Request $request, Response $response) {
        $mapper_member = new \App\MemberMapper($this->db);
        $member = $mapper_member->getUser();
        $msg = $this->flash->getMessages();

        return $this->view->render($response, 'admin/task/create.twig', ['mem' => $member, 'message' => $msg]);
    })->add($mwAdmin);

    $this->post('/add', function (Request $request, Response $response) {
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
        $user = explode('_', $data['member_id']);
        $userID = (int)$user[0];
        $slack_user = $user[1];

        $mapper = new \App\TaskMapper($this->db);
        $lastId = $mapper->addTask($data, $userID);

        $firstParam = 'You have  a new task  Client ID-'.$data['client_id'];

        \App\Utility::postToSlack($firstParam, $slack_user);
        //$mapper->addttached($filePath,$lastId);
        $this->flash->addMessage('success', 'Task is assigned!!!');

        return $response->withRedirect('/task/attached/' . $lastId);
       // return $response->withRedirect('/task/view/' . $lastId);
    });

    $this->get('/list[/{type}]', function (Request $request, Response $response, $arg) {
        $queryParams = $request->getQueryParams();
        $dateType = $request->getAttribute('type', 'all');

        $mapper_member = new \App\MemberMapper($this->db);
        $member = $mapper_member->getUser();

        $mapper = new \App\TaskMapper($this->db);
        if ($dateType == 'today') {
            $typeTitle = 'TODAY';
            $task = $mapper->getTodayTask($queryParams);
        } elseif ($dateType == 'all') {
            $typeTitle = 'ALL';
            $task = $mapper->getTask($queryParams);
            //var_dump($task); die();
        } elseif ($dateType == 'complete') {
            $typeTitle = 'COMPLETE';
            $task = $mapper->getCompleteTask($queryParams);
        } elseif ($dateType == 'pending') {
            $typeTitle = 'PENDING';
            $task = $mapper->getPendingTask($queryParams);
        }

        $update_message = $this->flash->getMessages();
        $response = $this->view->render($response, "admin/task/list.twig",
            [
                'task' => $task,
                'query' => $queryParams,
                'message' => $update_message,
                'typeTitle' => $typeTitle,
                'mem' => $member,
            ]);

        return $response;
    });

    /*$this->get('/getCounttask[/{type}]', function (Request $request, Response $response) {
        $dateType = $request->getAttribute('type');

        $mapper = new \App\TaskMapper($this->db);
        if ($dateType == 'todaytask') {
            $typeTitle = 'TODAYTASK';
            $task = $mapper->CountTaskToday();
        } elseif ($dateType == 'alltask') {
            $typeTitle = 'ALLTASK';
            $task = $mapper->countTaskAll();
            //var_dump($task); die();
        } elseif ($dateType == 'completetask') {
            $typeTitle = 'COMPLETETASK';
            $task = $mapper->CountTaskComplete();
        } elseif ($dateType == 'pendingtask') {
            $typeTitle = 'PENDINGTASK';
            $task = $mapper->CountTaskPending();
        }
        //$delete_message = $this->flash->getMessages();
        $response = $this->view->render($response, "tasklist.twig", ['task' => $task, 'typeTitle' => $typeTitle]);
        //return $response;
    });*/

    /**
     * Start  Member
     */
    $this->get('/member/list[/{type}]', function (Request $request, Response $response) {
        $dateType = $request->getAttribute('type' , 'all');
        $mapper = new \App\TaskMapper($this->db);
        if ($dateType == 'today') {
            $typeTitle = 'TODAY';

            $task = $mapper->memberTodayTask();
        }elseif($dateType == 'all') {
        $typeTitle = 'ALL';
        $task = $mapper->memberAllTask();

        }elseif($dateType == 'complete') {
            $typeTitle = 'COMPLETE';
            $task = $mapper->memberCompleteTask();
        }elseif($dateType == 'pending') {
            $typeTitle = 'PENDING';
            $task = $mapper->memberPendingTask();
        }

        $status_message = $this->flash->getMessages();
        $response = $this->view->render($response, "member/task.twig",
            ['task' => $task, 'message' => $status_message , 'typeTitle' => $typeTitle ]);

        return $response;
    });

    $this->post('/member/status', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        if (isset($data['task_id'][0])) {
            $mapper = new \App\TaskMapper($this->db);
            $sql = $mapper->updateMemberStatus($data);
            $this->flash->addMessage('success', 'Update! Successfuly Updated!!!');

            return $response->withRedirect('/task/members_task_list/today');
        } else {
            $this->flash->addMessage('error', 'Please check in task!!!');

            return $response->withRedirect('/task/members_task_list/today');
        }

    });
    $this->post('/member/change_status', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $mapper = new \App\TaskMapper($this->db);
        $id = $mapper->updateMemTaskStatus($data);


            if($data['status']==4){
                $f_param = 'CID: '.$data['cid'].' Task has been completed By -'.$_SESSION['user'][0]['username'];
                \App\Utility::postToSlack($f_param);
            }elseif ($data['status']==5){
                $f_param = 'CID: '.$data['cid'].' Task Invalid By -'.$_SESSION['user'][0]['username'];
                \App\Utility::postToSlack($f_param);
            }elseif ($data['status']==3){
                $f_param = 'CID: '.$data['cid'].' Task pending By -'.$_SESSION['user'][0]['username'];
                \App\Utility::postToSlack($f_param);
            }
        $this->flash->addMessage('success', 'Update! Successfuly Updated!!!');

        return $response->withRedirect('/task/view/'.$id);
    });

    /**
     * End Member
     */





    $this->get('/view/{id:[0-9]+}', function (Request $request, Response $response) {
        $id = $request->getAttribute('id');
        $mapper = new \App\TaskMapper($this->db);
        $details_data = $mapper->taskDetails($id);
        $att = $mapper->getAttacched($id);
        $create_message = $this->flash->getMessages();
        $all_comments = $mapper->getAllComments($id);
        $response = $this->view->render($response, "common/task/view.twig", [
            'details' => $details_data,
            'attached' => $att,
            'message' => $create_message,
            'all_comments' => $all_comments
        ]);

        return $response;
    });

    $this->get('/attach-zip/{id}', function (Request $request, Response $response) {
        $id = $request->getAttribute('id');
        $mapper = new \App\TaskMapper($this->db);
        $att = $mapper->getAttacched($id);

        //............
//var_dump($att);die();
        $files = $att;
        $rnd = rand(0, 10000);
        $zipname = $rnd . '-attach.zip';
        $zip = new ZipArchive;
        $zip->open($zipname, ZipArchive::CREATE);
        foreach ($files as $file) {
            $zip->addFile($file['attached_path']);
        }
        $zip->close();
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
    });


    $this->get('/edit/{id}', function (Request $request, Response $response) {
        $id = $request->getAttribute('id');
        $mapper = new \App\TaskMapper($this->db);
        $mapper_member = new \App\MemberMapper($this->db);
        $member = $mapper_member->getMember();
        $update_data = $mapper->getTaskId($id);
        $response = $this->view->render($response, "admin/task/edit.twig",
            ['update_data' => $update_data, 'member' => $member]);

        return $response;
    })->add($mwAdmin);

    $this->post('/update', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        //var_dump($data); die();
        $mapper = new \App\TaskMapper($this->db);
        $sql = $mapper->editTask($data);
        //var_dump($sql); die();
        $this->flash->addMessage('success', 'Update! Successfuly Updated!!!');

        //$this->flash->addMessage('update_message', 'Successfuly updated !!!');
        return $response->withRedirect('/task/view/' . $sql);
    });

    $this->get('/delete/{id}', function (Request $request, Response $response) {
        $id = $request->getAttribute('id');
        $mapper = new \App\TaskMapper($this->db);
        $mapper->taskDelete($id);
        $this->flash->addMessage('error', 'Task is Deleted!!!');

        return $response->withRedirect('/task/list/all');
    });

    $this->get('/attached/{id}', function (Request $request, Response $response) {
        $id = $request->getAttribute('id');
        $mapper = new \App\TaskMapper($this->db);
        $details_data = $mapper->taskDetails($id);
        $response = $this->view->render($response, "admin/task/attach.twig", ['details' => $details_data]);

        return $response;
    });


    $this->post('/upload/{id}', function (Request $request, Response $response) {
        $id = $request->getAttribute('id');
        $files = $request->getUploadedFiles();
        $newfile = $files['file'];
        $rnd = rand(1, 100000);
        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $uploadFileName = $newfile->getClientFilename();
            $filePath = "attached/$rnd.$uploadFileName";
            $newfile->moveTo($filePath);
            $mapper = new \App\TaskMapper($this->db);
            $mapper->addAttached($filePath, $id);
        }

    });




    $this->post('/admin_status', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        //var_dump($data['task_id'][0]);die();
        if (isset($data['task_id'][0])) {
            $mapper = new \App\TaskMapper($this->db);
            $id = $mapper->updateAdminStatus($data);
            $this->flash->addMessage('success', 'Update! Successfuly Updated!!!');

            return $response->withRedirect('/task/list/all');
        } else {
            $this->flash->addMessage('error', 'Please check in task!!!');

            return $response->withRedirect('/task/list/today');
        }

    });

    $this->post('/taska_status', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $mapper = new \App\TaskMapper($this->db);
        $sql = $mapper->updateTaskStatus($data);
        $this->flash->addMessage('update_message', 'Update! Successfuly Updated!!!');

        return $response->withRedirect('/task/list/today');
    });


    $this->post('/comment', function (Request $request, Response $response) {

        $files = $request->getUploadedFiles();
        $newfile = $files['comment_attach'];
        $rnd = rand(1, 100000);
        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $uploadFileName = $newfile->getClientFilename();
            $filePath = "attached/$rnd.$uploadFileName";
            $newfile->moveTo($filePath);
        }
        $data = $request->getParsedBody();
        $mapper = new \App\TaskMapper($this->db);
        $mapper->InsertComment($data, $filePath);
        return $response->withRedirect('/task/view/' . $data['task_id']);
    });

    $this->get('/admin_task', function (Request $request, Response $response) {
        $data=$request->getParsedBody();
        $mapper = new \App\TaskMapper($this->db);
        $data=$mapper->AdminTask($data);
        //var_dump($data); die();
        $response = $this->view->render($response, "admin/task/admin_task.twig",['task'=>$data]);
    });


    $this->get('/task_count', function (Request $request, Response $response) {
        //$data=$request->getParsedBody();
        $mapper = new \App\TaskMapper($this->db);
        $task_count=$mapper->countTaskAll();
       // var_dump($task_count); die();
        $response = $this->view->render($response, "admin/dashboard.twig",['taskall'=>$task_count]);
    });

    $this->get('/task_countToday', function (Request $request, Response $response) {
        //$data=$request->getParsedBody();
        $mapper = new \App\TaskMapper($this->db);
        $task_count=$mapper->countTaskToday();
        // var_dump($task_count); die();
        $response = $this->view->render($response, "admin/dashboard.twig",['tasktoday'=>$task_count]);
    });

    $this->get('/task_countPending', function (Request $request, Response $response) {
        //$data=$request->getParsedBody();
        $mapper = new \App\TaskMapper($this->db);
        $task_count=$mapper->countTaskPending();
        // var_dump($task_count); die();
        $response = $this->view->render($response, "admin/dashboard.twig",['taskpending'=>$task_count]);
    });

    $this->get('/task_countComplete', function (Request $request, Response $response) {
        //$data=$request->getParsedBody();
        $mapper = new \App\TaskMapper($this->db);
        $task_count=$mapper->countTaskComplete();
        // var_dump($task_count); die();
        $response = $this->view->render($response, "admin/dashboard.twig",['taskcom'=>$task_count]);
    });


})->add($mw);