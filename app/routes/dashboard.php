<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$app->get('/', function (Request $request, Response $response){
    $mapper = new \App\TaskMapper($this->db);
    $msg = $this->flash->getMessages();
    if ($_SESSION['user'][0]['role'] == 'Admin') {
        $path = 'admin';
        $count = $mapper->getAdminTaskCount();

    }
    if ($_SESSION['user'][0]['role'] == 'Member') {
        $path = 'member';
        $count = $mapper->getMemberTaskCount($_SESSION['user'][0]['id']);


    }

    return $this->view->render($response, $path . '/dashboard.twig', ['session' => $_SESSION,
        'msg' => $msg , 'count' => $count]);
})->add($mw);
