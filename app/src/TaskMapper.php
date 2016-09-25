<?php
namespace App;

class TaskMapper extends Mapper
{


    public function checkClientId($data)
    {

        $client_id = $data['client_id'];
        $sql = "SELECT client_id FROM tasks WHERE client_id='$client_id'";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetch();
        if (!empty($res)) {
            return true;
        } else {
            return false;
        }
    }


    public function addTask($data, $userID)
    {
        //var_dump($data); die();
        try {
            $date = $data['submission_date'];
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
            $stmt->bindParam(':user_id', $data['user_id']);
            $stmt->bindParam(':member_id', $userID);
            $stmt->bindParam(':client_id', $data['client_id']);
            $stmt->bindParam(':submission_date', date('Y-m-d'));
            $stmt->bindParam(':created_at', date('Y-m-d h:s:i'));
            $stmt->bindParam(':priority', $data['priority']);
            $stmt->execute();

            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw $e;

        }
    }


    public function getTask($data = false)
    {
        if (!empty($data)) {
            $client_id = $data['client_id'];
            $member_id = $data['member_id'];
            $created_at = $data['created_date'];
            // var_dump($created_at);die();
            $priority = $data['priority'];
            $task_status = $data['task_status'];
            $task_type = $data['task_type'];

            $whereArr = array();
            if ($client_id != "") {
                array_push($whereArr, "client_id = {$client_id}");
            }
            if ($member_id != "") {
                array_push($whereArr, "member_id = {$member_id}");
            }
            if ($created_at != "") {
                array_push($whereArr, "date(tasks.created_at) = {$created_at}");
            }
            if ($priority != "") {
                array_push($whereArr, "priority = {$priority}");
            }
            if ($task_status != "") {
                array_push($whereArr, "status = {$task_status}");
            }
            if ($task_type != "") {
                array_push($whereArr, "task_type = {$task_type}");
            }

            //var_dump($whereArr); die();
            $whereStr = implode(" AND ", $whereArr);

            //var_dump($whereStr); die();
            if (!empty($whereArr)) {
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
              left join users as member on tasks.member_id =member.id 
              WHERE {$whereStr}
              order by task_id DESC ";
            } else {
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
              tasks.created_at ,
              tasks.client_id,
              tasks.site_url,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name

              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id order by task_id DESC ";

            }


        } else {
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
              tasks.created_at ,
              tasks.client_id,
              tasks.site_url,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name

              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id order by task_id DESC ";

        }

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }


    public function getCompleteTask()
    {
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
              tasks.created_at ,
              tasks.client_id,
              tasks.site_url,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name

              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id where tasks.status=1 order by task_id DESC ";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }


    public function getPendingTask()
    {
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
              tasks.site_url,
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
        $sql = "SELECT COUNT(*) from tasks where status=1";
        $stmt = $this->db->query($sql);

        return $stmt;

    }


    public function getTodayTask($data = false)
    {

        if (!empty($data)) {

            $client_id = $data['client_id'];
            $member_id = $data['member_id'];
            $created_at = $data['created_at'];
            $priority = $data['priority'];
            $task_status = $data['task_status'];
            $task_type = $data['task_type'];

            $whereArr = array();
            if ($client_id != "") {
                array_push($whereArr, "client_id = {$client_id}");
            }
            if ($member_id != "") {
                array_push($whereArr, "member_id = {$member_id}");
            }
            if ($created_at != "") {
                array_push($whereArr, "date(tasks.created_at) = {$created_at}");
            }
            if ($priority != "") {
                array_push($whereArr, "priority = {$priority}");
            }
            if ($task_status != "") {
                array_push($whereArr, "status = {$task_status}");
            }
            if ($task_type != "") {
                array_push($whereArr, "task_type = {$task_type}");
            }
            array_push($whereArr, "date(tasks.created_at)=curdate()");

            //var_dump($whereArr); die();
            $whereStr = implode(" AND ", $whereArr);

            //var_dump($whereStr); die();
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
              left join users as member on tasks.member_id =member.id  WHERE {$whereStr} order by task_id DESC ";

        } else {

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
              tasks.site_url,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id where date(tasks.created_at)=curdate() order by task_id DESC ";

        }


        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function memberAllTask()
    {
        $member_id = $_SESSION['user'][0]['id'];
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
              tasks.site_url,
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

    public function memberTodayTask()
    {
        $member_id = $_SESSION['user'][0]['id'];
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
              tasks.site_url,
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

    public function memberCompleteTask()
    {
        $member_id = $_SESSION['user'][0]['id'];
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
              tasks.site_url,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id where tasks.status=1
              AND tasks.member_id='$member_id' order by task_id DESC ";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }


    public function memberPendingTask()
    {
        $member_id = $_SESSION['user'][0]['id'];
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
              tasks.site_url,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id where tasks.status=3
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
              tasks.site_url,
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


    public function addAttached($filePath, $id)
    {
        try {

            $stmt = $this->db->prepare("INSERT INTO attached (
            task_id,
            attached_path)VALUES(
            :task_id,
            :attached_path)");
            $stmt->bindParam(':task_id', $id);
            $stmt->bindParam(':attached_path', $filePath);
            $stmt->execute();

            return true;

        } catch (Exception $e) {
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
              tasks.site_url,
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
        //var_dump($data); die();
        $taskID = array_values($data['task_id']);
        $ids = join(',', $taskID);

        /*        var_dump($data['task_id']);
                var_dump($data['status']);*/
        //var_dump($ids);
        //die();
        try {

            $stmt = $this->db->prepare("UPDATE  tasks SET status = :status , site_url = :site_url where id in ($ids)");
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':site_url', $data['site_url']);
            //$stmt->bindParam(':ids',$ids);
            $stmt->execute();

            // var_dump($stmt->debugDumpParams()); die();

            return true;

        } catch (Exception $e) {
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
        try {

            $stmt = $this->db->prepare("UPDATE  tasks SET status = :status where id in ($ids)");
            $stmt->bindParam(':status', $data['status']);
            //$stmt->bindParam(':ids',$ids);
            $stmt->execute();

            // var_dump($stmt->debugDumpParams()); die();

            return true;

        } catch (Exception $e) {
            throw $e;
        }

    }


    public function InsertComment($data, $filePath)
    {
        //var_dump($data);die();
        //var_dump();die();
        $username = $_SESSION['user'][0]['username'];
        try {

            $stmt = $this->db->prepare("INSERT INTO comments (
            comments,
            task_id,
            user_id,
            username,
            comment_attach
           )VALUES (
            :commennts,
            :task_id,
            :user_id,
            :username,
            :comment_attach
            )");

            $stmt->bindParam(':commennts', ucfirst($data['comments']));
            $stmt->bindParam(':task_id', $data['task_id']);
            $stmt->bindParam(':user_id', $data['user_id']);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':comment_attach', $filePath);
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
        $taskID = $data['task_id'];
        try {
            //var_dump($taskID); die();

            $sql = "UPDATE tasks SET status = :status WHERE  id ='$taskID'";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $data['status']);
            //$stmt->bindParam(':ids',$ids);
            $stmt->execute();

            // var_dump($stmt->debugDumpParams()); die();

            return true;

        } catch (Exception $e) {
            throw $e;
        }

    }

    public function updateMemTaskStatus($data)
    {
        $taskID = $data['task_id'];
        //var_dump($data);die();
        try {
            $sql = "UPDATE  tasks SET status = :status , site_url = :site_url  WHERE  id ='$taskID'";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':site_url', $data['site_url']);
            $stmt->execute();
            return $taskID;

        } catch (Exception $e) {
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
            $details = $this->taskDetails($taskID);

            return $details['task_id'];

        } catch (Exception $e) {
            throw $e;

        }

    }


    public function getSearchTask()
    {
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
              left join users as member on tasks.member_id =member.id where order by task_id DESC ";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function profileUpdate($data)
    {
        $user_id = $data['user_id'];
        //$password=$data['username'];
        //var_dump($user_id); die();
        //var_dump($password); die();
        try {

            $stmt = $this->db->prepare("UPDATE  users SET username = :username, password=:password where id in ($user_id)");
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':password', sha1($data['password']));
            $stmt->execute();

            // var_dump($stmt->debugDumpParams()); die();

            return true;

        } catch (Exception $e) {
            throw $e;
        }

    }

    public function AdminTask()
    {
        $member_id = $_SESSION['user'][0]['id'];
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
              tasks.site_url,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id where tasks.member_id='$member_id' 
              AND tasks.member_id='$member_id' order by task_id DESC ";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }


}