<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response) {
    $msg = $this->flash->getMessages();

    if($_SESSION['user'][0]['role'] =='Admin') {
        $path = 'admin';
    }
    if($_SESSION['user'][0]['role'] =='Member') {
        $path = 'member';
    }
    
    return $this->view->render($response, $path.'/dashboard.twig', ['session' => $_SESSION, 'msg' => $msg]);
})->add($mw);
