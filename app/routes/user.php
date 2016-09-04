<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/login', function (Request $request, Response $response) {

//var_dump(R::dispense('tasks')); die();
// return $this->view->render($response, 'layout.twig');
return $this->view->render($response, 'login.twig');
});

$app->post('/login', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $mapper = new \App\UserMapper($this->db);
    $chkData = $mapper->checkUser($data);
    $_SESSION['username']= $chkData;
    if(!empty($chkData)){
        return $response->withStatus(302)->withHeader('Location', '/');
    }

    //return $this->view->render($response, 'login.twig');
});    