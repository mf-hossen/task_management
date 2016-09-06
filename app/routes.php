<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



require "routes/task.php";
require "routes/user.php";



$app->get('/', function (Request $request, Response $response) {
    $msg = $this->flash->getMessages();
    return $this->view->render($response, 'welcome.twig',['session'=>$_SESSION,'msg'=>$msg]);
})->add($mw);





