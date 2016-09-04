<?php
namespace App;



class EmployeeMapper extends Mapper
{
    public function getEmployee() {
        $sql = "SELECT employee.id ,employee.name,employee.work_time , employee.salary, department.name as department_name , designation.name as designation_name
FROM `employee` 
join department join designation 
on employee.department_id = department.id  and employee.designation_id = designation.id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function addEmployee($data)
    {
        {
            // var_dump($data); die();
            try {
                $stmt = $this->db->prepare("INSERT INTO employee (name,email,phone,gender,designation_id,department_id,work_time,salary,description)VALUES (:name,:email,:phone,:gender,:designation_id,:department_id,:work_time,:salary,:description)");
                $stmt->bindParam(':name', $data['name']);
                $stmt->bindParam(':email', $data['email']);
                $stmt->bindParam(':phone', $data['phone']);
                $stmt->bindParam(':gender', $data['gender']);
                $stmt->bindParam(':designation_id', $data['designation_id']);
                $stmt->bindParam(':department_id', $data['department_id']);
                $stmt->bindParam(':work_time', $data['work_time']);
                $stmt->bindParam(':salary', $data['salary']);
                $stmt->bindParam(':description', $data['description']);
                $stmt->execute();

                return $this->db->lastInsertId();

            } catch (Exception $e) {
                throw $e;

            }
        }
    }

    public function getDetails($id)
    {

        $sql = "SELECT employee.id as employee_id,employee.name as employee_name,employee.email,employee.phone,employee.gender, employee.work_time , employee.salary,employee.description, designation.id as desi_id,department.id as desi_id, department.name as department_name , designation.name as designation_name
FROM `employee` 
join department join designation 
on employee.department_id = department.id  and employee.designation_id = designation.id 
where employee.id='$id'";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch();
        //var_dump($row);
        return $row;
    }

    public function getbyId($id)
    {
        $sql = "SELECT employee.id as employee_id,employee.name as employee_name,employee.email,employee.phone,employee.gender, employee.work_time , employee.salary,employee.description, designation.id as desi_id,department.id as desi_id, department.name as department_name , designation.name as designation_name
FROM `employee` 
join department join designation 
on employee.department_id = department.id  and employee.designation_id = designation.id 
where employee.id='$id'";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch();
        return $row;
    }

    public function editEmployee($data)
    {
        try {

            $id = $data['id'];
            $stmt = $this->db->prepare("update employee set name=:name,email=:email,phone=:phone,gender=:gender, designation_id=:designation_id, department_id=:department_id, work_time=:work_time, salary=:salary where id='$id'");
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':gender', $data['gender']);
            $stmt->bindParam(':designation_id', $data['designation_id']);
            $stmt->bindParam(':department_id', $data['department_id']);
            $stmt->bindParam(':work_time', $data['work_time']);
            $stmt->bindParam(':salary', $data['salary']);

            $stmt->execute();

            $details = $this->getDetails($id);
            return $details['employee_id'];

        } catch (Exception $e) {
            throw $e;

        }

    }

    public function empDelete($id)
    {
        //var_dump($id);
        $sql = "DELETE FROM employee where id='$id'";
        $stmt = $this->db->query($sql);
        return $stmt;
    }


}


