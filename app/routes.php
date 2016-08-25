<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response) {
    $this->logger->addInfo("Ticket list");
    $this->view->render($response, 'home.twig');
    return $response;
});

$app->get('/employee', function (Request $request, Response $response) {
    $this->logger->addInfo("Ticket list");
    $mapper = new \App\EmployeeMapper($this->db);
    $employee = $mapper->getEmployee();
    $delete_message = $this->flash->getMessages();

    //var_dump($delete_message); die();
    $this->view->render($response, 'employee.twig' , ['emp' => $employee,'msg'=>$delete_message]);
    return $response;
});

$app->get('/add', function (Request $request, Response $response) {
    $mapper = new \App\EmployeeMapper($this->db);
    $mapper_dept = new \App\DepartmentMapper($this->db);
    $dept=$mapper_dept->getDepartment();
    $mapper_desi = new \App\DesignationMapper($this->db);
    $desi=$mapper_desi->getDesignation();
    //var_dump($desi);
    $this->view->render($response, "insert.twig",['dept'=>$dept,'desi'=>$desi]);
});

$app->post('/insert', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $mapper = new \App\EmployeeMapper($this->db);
    $id=$mapper->addEmployee($data);
    //$this->flash->addMessage('message', 'Successfuly employee added !!!');
    return $response->withRedirect('/details/'.$id);
});

$app->get('/details/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\EmployeeMapper($this->db);
    $details_data = $mapper->getDetails($id);
    //var_dump($details_data); die();
    $messages = $this->flash->getMessages();
    $response = $this->view->render($response, "details.twig",['details'=>$details_data,'msg'=>$messages]);
    return $response;
});

$app->get('/update/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\EmployeeMapper($this->db);
    $update_data = $mapper->getbyId($id);
    $mapper_dept = new \App\DepartmentMapper($this->db);
    $dept=$mapper_dept->getDepartment();
    $mapper_desi = new \App\DesignationMapper($this->db);
    $desi=$mapper_desi->getDesignation();
    $response = $this->view->render($response, "update.twig",['update_data'=>$update_data,'dept'=>$dept,'desi'=>$desi]);
    return $response;
});

$app->post('/update', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    //var_dump($data);
    $mapper = new \App\EmployeeMapper($this->db);
    $sql=$mapper->editEmployee($data);
    //$this->flash->addMessage('update_message', 'Successfuly updated !!!');
    return $response->withRedirect('/details/'.$sql);
});

$app->get('/delete/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mapper = new \App\EmployeeMapper($this->db);
    $mapper->empDelete($id);
    $this->flash->addMessage('delete_message', 'Employee Deleted!!!');
    return $response->withRedirect('/employee');
});






