<?php
namespace App;

class TaskMapper extends Mapper
{


    public function  checkClientId($data){

        $client_id  = $data['client_id'];
        $sql = "SELECT client_id FROM tasks WHERE client_id='$client_id'";
        $stmt = $this->db->query($sql);
        $res= $stmt->fetch();
        if (!empty($res)){
            return true;
        }else{
            return false;
        }
    }


    public function addTask($data)
    {
        //var_dump($data); die();
        try {
            $date=$data['submission_date'];
            $stmt = $this->db->prepare("INSERT INTO tasks (
            description,
            task_type,
            user_id,
            member_id,
            client_id,
            submission_date,
            created_at,
            priority)VALUES (
            :description,
            :task_type,
            :user_id,
            :member_id,
            :client_id,
            :submission_date,
            :created_at,
            :priority)");

            $stmt->bindParam(':description', ucfirst($data['description']));
            $stmt->bindParam(':task_type', $data['task_type']);
            $stmt->bindParam(':user_id',$data['user_id']);
            $stmt->bindParam(':member_id',$data['member_id']);
            $stmt->bindParam(':client_id',$data['client_id']);
            $stmt->bindParam(':submission_date',date('Y-m-d'));
            $stmt->bindParam(':created_at',date('Y-m-d h:s:i'));
            $stmt->bindParam(':priority',$data['priority']);
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
              concat(users.first_name , ' ', users.last_name ) as users_full_name,
              users.role, 
              tasks.description,
              tasks.id as task_id,
              tasks.status,
              tasks.task_type,
              tasks.member_id,
              tasks.created_at,
              tasks.client_id,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name

              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id order by task_id DESC ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }


    public function getCompleteTask() {
        $sql = "SELECT 
              users.id as user_id, 
              users.username, 
              concat(users.first_name , ' ', users.last_name ) as users_full_name,
              users.role, 
              tasks.description,
              tasks.id as task_id,
              tasks.status,
              tasks.task_type,
              tasks.member_id,
              tasks.created_at,
              tasks.client_id,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name

              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id where tasks.status=1 order by task_id DESC ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }


    public function getPendingTask() {
        $sql = "SELECT 
              users.id as user_id, 
              users.username, 
              concat(users.first_name , ' ', users.last_name ) as users_full_name,
              users.role, 
              tasks.description,
              tasks.id as task_id,
              tasks.status,
              tasks.task_type,
              tasks.member_id,
              tasks.created_at,
              tasks.client_id,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name

              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id where tasks.status=3 order by task_id DESC ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getCountComplete()
    {
       $sql="SELECT COUNT(*) from tasks where status=1";
        $stmt = $this->db->query($sql);
        return $stmt;

    }


    public function getTodayTask() {

        $sql = "SELECT 
              users.id as user_id, 
              users.username, 
              concat(users.first_name , ' ', users.last_name ) as users_full_name,
              users.role, 
              tasks.description,
              tasks.status,
              tasks.id as task_id,
              tasks.task_type,
              tasks.member_id,
              tasks.created_at,
              tasks.client_id,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id where date(tasks.created_at)=curdate() order by task_id DESC ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function memberAllTask() {
        $member_id=$_SESSION['user'][0]['id'];
        $sql = "SELECT 
              users.id as user_id, 
              users.username,
              concat(users.first_name , ' ', users.last_name ) as users_full_name,
              users.role,  
              tasks.description,
              tasks.id as task_id,
              tasks.task_type,
              tasks.status,
              tasks.member_id,
              tasks.client_id,
              tasks.created_at,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id
              where tasks.member_id='$member_id' order by task_id DESC ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function memberTodayTask() {
        $member_id=$_SESSION['user'][0]['id'];
        $sql = "SELECT 
              users.id as user_id, 
              users.username,
              concat(users.first_name , ' ', users.last_name ) as users_full_name,
              users.role, 
              tasks.description,
              tasks.id as task_id,
              tasks.status,
              tasks.task_type,
              tasks.member_id,
              tasks.client_id,
              tasks.created_at,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id where date(tasks.created_at)=curdate()
              AND tasks.member_id='$member_id' order by task_id DESC ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }


    public function taskDetails($id)
    {
        //var_dump($id); die();
        $sql = "SELECT 
              users.id as user_id, 
              users.username,
              concat(users.first_name , ' ', users.last_name ) as users_full_name,
              users.role, 
              
              tasks.id as task_id,               
              tasks.description, 
              tasks.task_type,
              tasks.member_id,
              tasks.client_id,
              tasks.status,
              tasks.created_at,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name

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

            $stmt = $this->db->prepare("INSERT INTO attached (
            task_id,
            attached_path)VALUES(
            :task_id,
            :attached_path)");
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
              tasks.id as task_id,
              tasks.description, 
              tasks.task_type,
              tasks.member_id,
              tasks.client_id,
              tasks.created_at,
              tasks.priority,
              tasks.submission_date,
              member.username as membername
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id 
              where tasks.id='$id'";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch();
        return $row;
    }

    public function getAttacched($id)
    {
        $sql = "SELECT * FROM attached WHERE task_id='$id'";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetchAll();
        return $res;
    }

    public function taskDelete($id)
    {
        //var_dump($id);
        $sql = "DELETE FROM tasks where id='$id'";
        $stmt = $this->db->query($sql);
        return $stmt;
    }

    public function updateMemberStatus($data)
    {
       // var_dump($data); die();
        $taskID = array_values($data['task_id']);
        $ids = join(',', $taskID);

/*        var_dump($data['task_id']);
        var_dump($data['status']);*/
        //var_dump($ids);
        //die();
        try{

            $stmt = $this->db->prepare("UPDATE  tasks SET status = :status where id in ($ids)");
            $stmt->bindParam(':status',$data['status']);
            //$stmt->bindParam(':ids',$ids);
            $stmt->execute();
           // var_dump($stmt->debugDumpParams()); die();

            return true;

        }catch (Exception $e){
            throw $e;
        }

    }


    public function updateAdminStatus($data)
    {
        // var_dump($data); die();
        $taskID = array_values($data['task_id']);
        $ids = join(',', $taskID);

        /*        var_dump($data['task_id']);
                var_dump($data['status']);*/
        //var_dump($ids);
        //die();
        try{

            $stmt = $this->db->prepare("UPDATE  tasks SET status = :status where id in ($ids)");
            $stmt->bindParam(':status',$data['status']);
            //$stmt->bindParam(':ids',$ids);
            $stmt->execute();
            // var_dump($stmt->debugDumpParams()); die();

            return true;

        }catch (Exception $e){
            throw $e;
        }

    }


    public function InsertComment($data)
    {
        //var_dump($data);die();
        try {

            $stmt = $this->db->prepare("INSERT INTO comments (
            comments,
            task_id,
            user_id,
            username)VALUES (
            :commennts,
            :task_id,
            :user_id,
            :username)");

            $stmt->bindParam(':commennts', ucfirst($data['comments']));
            $stmt->bindParam(':task_id', $data['task_id']);
            $stmt->bindParam(':user_id',$data['user_id']);
            $stmt->bindParam(':username',$data['username']);
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw $e;

        }
    }

    public function getAllComments($id)
    {
        $sql = "SELECT * FROM comments WHERE task_id='$id'";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetchAll();
        return $res;
    }



    public function updateTaskStatus($data)
    {
        //var_dump($data); die();
        $taskID = $data['task_id'];
        //$ids = $taskID;

        //var_dump($data['task_id']);
        //var_dump($data['status']);
        //var_dump($ids);
        //die();
        try{
            //var_dump($taskID); die();

            $sql = "UPDATE tasks SET status = :status WHERE  id ='$taskID'";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status',$data['status']);
            //$stmt->bindParam(':ids',$ids);
            $stmt->execute();
            // var_dump($stmt->debugDumpParams()); die();

            return true;

        }catch (Exception $e){
            throw $e;
        }

    }

    public function updateMemTaskStatus($data)
    {


        //var_dump($data); die();
        $taskID = $data['task_id'];
        //$ids = $taskID;

        //var_dump($data['task_id']);
        //var_dump($data['status']);
        //var_dump($ids);
        //die();
        try{
            //var_dump($taskID); die();

            $sql = "UPDATE tasks SET status = :status WHERE  id ='$taskID'";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status',$data['status']);
            //$stmt->bindParam(':ids',$ids);
            $stmt->execute();
            // var_dump($stmt->debugDumpParams()); die();

            return true;

        }catch (Exception $e){
            throw $e;
        }
    }

    public function editTask($data)
    {
        $taskID = $data['task_id'];
        //var_dump($taskID);die();
        try {

            //$id = $data['id'];
            //var_dump($id); die();
            $stmt = $this->db->prepare("update tasks set client_id=:client_id,description=:description,task_type=:task_type, member_id=:member_id, priority=:priority where id='$taskID'");
            $stmt->bindParam(':client_id', $data['client_id']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':task_type', $data['task_type']);
            $stmt->bindParam(':member_id', $data['member_id']);
            $stmt->bindParam(':priority', $data['priority']);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            throw $e;

        }

    }
}
