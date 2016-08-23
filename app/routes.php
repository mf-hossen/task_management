<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response) {
    $this->logger->addInfo("Ticket list");
    $mapper = new \App\StudentMapper($this->db);
    $students = $mapper->getStudent();
    $this->view->render($response, 'another.twig' , ['students' => $students]);
    return $response;
});