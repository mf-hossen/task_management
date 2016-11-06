<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$app->get('/', function (Request $request, Response $response){
    $mapper = new \App\TaskMapper($this->db);
    $msg = $this->flash->getMessages();
    $barChartData = $mapper->getBarcharTask();
    $countMemberTask = $mapper->getMemberTodayTaskCount();
    if ($_SESSION['user'][0]['role'] == 'Admin') {
        $path = 'admin';
        $count = $mapper->getAdminTaskCount();
        $barChartData = $mapper->getBarcharTask();
    }
    if ($_SESSION['user'][0]['role'] == 'Member') {
        $path = 'member';
        $count = $mapper->getMemberTaskCount($_SESSION['user'][0]['id']);
        $countMemberTask = $mapper->getMemberTodayTaskCount();
        $barChartData = $mapper->getBarcharTaskMember();


    }
    /*if ($_SESSION['user'][0]['role'] == 'Admin') {
        $path = 'admin';
        $count = $mapper->getToadyTaskCount();

    }*/

    //var_dump($count); die();
    return $this->view->render($response, $path . '/dashboard.twig', ['session' => $_SESSION,
        'msg' => $msg, 'count' => $count, 'countMemberTask' => $countMemberTask, 'barChartData' => $barChartData]);
//    return $this->view->render($response, $path. '/morris-data.twig',['barChartData'=>$barChartData]);
})->add($mw);
