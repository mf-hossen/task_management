<?php
namespace App;

class TaskMapper extends Mapper
{
    public function addTask($data)
    {
        //var_dump($data); die();
        try {
            $date=$data['submission_date'];
            $stmt = $this->db->prepare("INSERT INTO tasks (title,description,status,user_id,member_id,client_id,submission_date,created_at)VALUES (:title,:description,:status,:user_id,:member_id,:client_id,:submission_date,:created_at)");
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':user_id',$data['user_id']);
            $stmt->bindParam(':member_id',$data['member_id']);
            $stmt->bindParam(':client_id',$data['client_id']);
            $stmt->bindParam(':submission_date',$data['submission_date']);
            $stmt->bindParam(':created_at',date('Y-m-d h:s:i'));
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw $e;

        }
    }

    public function getTask() {
        $sql = "SELECT 
              users.id as user_id, 
              users.username, 
              users.role, 
              tasks.title, 
              tasks.description,
              tasks.id as task_id,
              tasks.status,
              tasks.member_id,
              tasks.created_at,
              member.username as membername
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function memberTaskList() {
        $member_id=$_SESSION['user'][0]['id'];
        $sql = "SELECT * from tasks where member_id='$member_id'";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }


    public function taskDetails($id)
    {
        //var_dump($id); die();
        $sql = "SELECT 
              users.id as user_id, 
              users.username, 
              users.role, 
              tasks.title, 
              tasks.description, 
              tasks.status,
              tasks.member_id,
              tasks.created_at,
              member.username as membername
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id 
              where tasks.id='$id'";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch();
        //var_dump($row); die();
        return $row;
    }



    public function addAttached($filePath,$id)
    {
        try{

            $stmt = $this->db->prepare("INSERT INTO attached (task_id,attached_path)VALUES(:task_id,:attached_path)");
            $stmt->bindParam(':task_id',$id);
            $stmt->bindParam(':attached_path',$filePath);
            $stmt->execute();
            return true;

        }catch (Exception $e){
            throw $e;
        }
    }


    public function getTaskId($id)
    {
        $sql = "SELECT 
              users.id as user_id, 
              users.username, 
              users.role, 
              tasks.title, 
              tasks.description, 
              tasks.status,
              tasks.member_id,
              tasks.created_at,
              member.username as membername
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id 
              where tasks.id='$id'";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch();
        return $row;
    }

}
