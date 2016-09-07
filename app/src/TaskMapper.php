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
        $sql = "SELECT * from tasks";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }


    public function addttached($filePath,$id)
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

}
