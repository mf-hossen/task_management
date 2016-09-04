<?php
namespace App;

class TaskMapper extends Mapper
{
    public function addTask($data)
    {
        //var_dump($data); die();
        try {
            $stmt = $this->db->prepare("INSERT INTO tasks (title,description,status,user_id,member_id,created_at)VALUES (:title,:description,:status,:user_id,:member_id,:created_at)");
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':user_id',$data['user_id']);
            $stmt->bindParam(':member_id',$data['member_id']);
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

}
