<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/login', function (Request $request, Response $response) {

//var_dump(R::dispense('tasks')); die();
// return $this->view->render($response, 'layout.twig');
    $msg = $this->flash->getMessages();

    return $this->view->render($response, 'login.twig', ['message' => $msg]);
});

$app->post('/login', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $mapper = new \App\UserMapper($this->db);
    $chkData = $mapper->checkUser($data);
    $_SESSION['user'] = $chkData;
    //var_dump($_SESSION['user'][0]['first_name']);die();
    if (!empty($chkData)) {
        \App\Utility::postToSlack($_SESSION['user'][0]['first_name'] . " " . $_SESSION['user'][0]['last_name'] . " has entered into Task Manager System");

        return $response->withStatus(302)->withHeader('Location', '/');
    } else {
        $this->flash->addMessage('error', 'Username Or Password Invalid!!');

        return $response->withStatus(302)->withHeader('Location', '/login');
    }
});

$app->get('/logout', function (Request $request, Response $response) {
    session_destroy();

    return $response->withStatus(302)->withHeader('Location', '/login');

});
