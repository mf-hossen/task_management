<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/login', function (Request $request, Response $response) {

//var_dump(R::dispense('tasks')); die();
// return $this->view->render($response, 'layout.twig');
    $msg = $this->flash->getMessages();
return $this->view->render($response, 'login.twig',['message'=>$msg]);
});

$app->post('/login', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $mapper = new \App\UserMapper($this->db);
    $chkData = $mapper->checkUser($data);
    $_SESSION['user']= $chkData;
    //var_dump($_SESSION['user'][0]['first_name']);die();
    if(!empty($chkData)){
        \App\Utility::postToSlack($_SESSION['user'][0]['first_name'] ." ". $_SESSION['user'][0]['last_name']." has entered into Task Manager System");
        return $response->withStatus(302)->withHeader('Location', '/');
    }else{
        $this->flash->addMessage('error', 'Username Or Password Invalid!!');
        return $response->withStatus(302)->withHeader('Location', '/login');
    }
});

$app->get('/logout', function(Request $request,  Response $response){
    session_destroy();
    return $response->withStatus(302)->withHeader('Location', '/login');

});

$app->get('/member-create', function (Request $request, Response $response){
    if($_SESSION['user'][0]['role'] =='Admin'){
        $msg = $this->flash->getMessages();
        return $this->view->render($response,'member-create.twig',['message'=>$msg]);
    }else{
        $this->flash->addMessage('error', 'Warning!!! You are not authorized to this page');
        return $response->withStatus(302)->withHeader('Location', '/');
    }

})->add($mw);

$app->post('/member-create', function (Request $request, Response $response){

    $data = $request->getParsedBody();
    $map = new \App\UserMapper($this->db);
    $check_user = $map->checkUserName($data);
    if ($check_user==true){
        $this->flash->addMessage('error', 'Username Already exists');
        return $response->withStatus(302)->withHeader('Location', '/member-create');
    }

    if(strlen($data['password']) >= 6){

        if ($data['password']==$data['confirm_password']){
            $mapper = new \App\UserMapper($this->db);
            $lastID = $mapper->createUser($data);
            $this->flash->addMessage('success', 'New Member has beed created!!');
            return $response->withStatus(302)->withHeader('Location', '/member-list');
        }else{
            $this->flash->addMessage('error', 'Password don\'t match');
            return $response->withStatus(302)->withHeader('Location', '/member-create');
        }
    }else{
        $this->flash->addMessage('error', 'Password should be minimume six character');
        return $response->withStatus(302)->withHeader('Location', '/member-create');
    }




})->add($mw);

$app->get('/member-list', function(Request $request,Response $response){
    if($_SESSION['user'][0]['role'] =='Admin'){
        $msg = $this->flash->getMessages();
        $mapper = new \App\UserMapper($this->db);
        $data =$mapper->memberList();
        //$this->flash->addMessage('success', 'Warning!!! You are not authorized to this page');
        return $this->view->render($response,'member-list.twig',['data'=>$data,'message'=>$msg]);
    }else{
        $this->flash->addMessage('error', 'Warning!!! You are not authorized to this page');
        return $response->withStatus(302)->withHeader('Location', '/');
    }
})->add($mw);


$app->get('/member-list/{id}', function(Request $request,Response $response){
    $id = $request->getAttribute('id');
        echo 'success ' .$id;
})->add($mw);

$app->get('/member-edit/{id}', function(Request $request,Response $response){
    if($_SESSION['user'][0]['role'] =='Admin') {
        $id = $request->getAttribute('id');
        $mapper = new \App\UserMapper($this->db);
        $data = $mapper->memberListById($id);
        return $this->view->render($response,'member-edit.twig',['data'=>$data]);
    }else{
        $this->flash->addMessage('error', 'Warning!!! You are not authorized to this page');
        return $response->withStatus(302)->withHeader('Location', '/');
    }
})->add($mw);

$app->post('/member-edit', function(Request $request,Response $response){
    if($_SESSION['user'][0]['role'] =='Admin') {
        $data = $request->getParsedBody();
        $mapper = new \App\UserMapper($this->db);
        $u = $mapper->memberEdit($data);
        if($u==true){
            $this->flash->addMessage('success', 'New Member has beed created!!');
            return $response->withStatus(302)->withHeader('Location', '/member-list/'.$data['id'].'');
        }
    }else{
        $this->flash->addMessage('error', 'Warning!!! You are not authorized to this page');
        return $response->withStatus(302)->withHeader('Location', '/');
    }
})->add($mw);

$app->get('/member-delete/{id}', function(Request $request,Response $response){
    if($_SESSION['user'][0]['role'] =='Admin') {
        $id = $request->getAttribute('id');
        $mapper = new \App\UserMapper($this->db);
        $u = $mapper->memberDelete($id);
        if($u==true){
            $this->flash->addMessage('error', ' Member has beed deleted!!');
            return $response->withStatus(302)->withHeader('Location','/member-list');
        }
    }else{
        $this->flash->addMessage('error', 'Warning!!! You are not authorized to this page');
        return $response->withStatus(302)->withHeader('Location', '/');
    }
})->add($mw);