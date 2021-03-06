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
            //$date = $data['submission_date'];
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
       if(!isset($data['page'])){
            $page = 1;
       }else{

           $page = $data['page'];
       }

        $limit = 10;
        $offset = ($page - 1) * $limit;


        if (!empty($data)) {
            if (isset($data['client_id'])) {
                $client_id = $data['client_id'];
            } else {
                $client_id = "";

            }

            if (isset($data['admin_id'])) {
                $admin_id = $data['admin_id'];
            } else {
                $admin_id = "";

            }
            if (isset($data['member_id'])) {
                $member_id = $data['member_id'];
            } else {
                $member_id = "";
            }

            if (isset($data['created_date'])) {
                $created_at = $data['created_date'];
            } else {
                $created_at = "";
            }
            if (isset($data['action_date'])) {
                $end_at = $data['action_date'];
            } else {
                $end_at = "";
            }

            if (isset($data['start_date'])) {
                $start_date = $data['start_date'];
            } else {
                $start_date = "";
            }
            if (isset($data['end_date'])) {
                $end_date = $data['end_date'];
            } else {
                $end_date = "";
            }

            if (isset($data['astart_date'])) {
                $astart_date = $data['astart_date'];
            } else {
                $astart_date = "";
            }
            if (isset($data['aend_date'])) {
                $aend_date = $data['aend_date'];
            } else {
                $aend_date = "";
            }

            if (isset($data['priority'])) {
                $priority = $data['priority'];
            } else {
                $priority = "";
            }

            if (isset($data['task_status'])) {
                $task_status = $data['task_status'];
            } else {
                $task_status = "";
            }

            if (isset($data['task_type'])) {
                $task_type = $data['task_type'];
            } else {
                $task_type = "";
            }

            $total_page = $this->getAdminTaskCount();
            $whereArr = array();
            if ($client_id != "") {
                array_push($whereArr, "client_id = {$client_id}");
            }
            if ($admin_id != "") {
                array_push($whereArr, "user_id = {$admin_id}");
            }
            if ($member_id != "") {
                array_push($whereArr, "member_id = {$member_id}");
            }
            if ($created_at != "") {
                array_push($whereArr, "date(tasks.created_at) = '{$created_at}'");
            }
            if ($end_at != "") {
                array_push($whereArr, "date(tasks.action_date) = '{$end_at}'");
            }
            if ($start_date != "" && $end_date != "") {
                array_push($whereArr, "date(tasks.created_at) between '" . $start_date . "' and '" . $end_date . "' ");
            }
            if ($astart_date != "" && $aend_date != "") {
                array_push($whereArr, "date(tasks.action_date) between '" . $astart_date . "' and '" . $aend_date . "' ");
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
              tasks.action_date,
              tasks.client_id,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name

              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id 
              WHERE {$whereStr}
              order by task_id DESC  LIMIT $offset, $limit";
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
              tasks.created_at,
              tasks.action_date,
              tasks.client_id,
              tasks.site_url,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name

              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id = member.id order by task_id DESC  LIMIT $offset, $limit";

            }


        } else {
          // var_dump(111); die();
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
              tasks.action_date,
              tasks.client_id,
              tasks.site_url,
              tasks.priority,
              tasks.action_date,

              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name

              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id order by task_id DESC  LIMIT $offset, $limit ";

        }

        //print_r($sql); die();
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
              tasks.created_at,
              tasks.action_date,
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
              tasks.action_date,
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

    public function getPauseTask()
    {
        $sql = "SELECT
              users.id AS user_id, 
              users.username, 
              concat(users.first_name , ' ', users.last_name ) AS users_full_name,
              users.role, 
              tasks.description,
              tasks.id AS task_id,
              tasks.status,
              tasks.task_type,
              tasks.member_id,
              tasks.created_at,
              tasks.action_date,
              tasks.client_id,
              tasks.site_url,
              tasks.priority,
              member.username AS membername,
              concat(member.first_name , ' ', member.last_name ) AS members_full_name

              FROM `tasks` 
              LEFT JOIN users ON tasks.user_id = users.id 
              LEFT JOIN users AS member ON tasks.member_id =member.id WHERE tasks.status=6 ORDER BY task_id DESC ";
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
           // var_dump(112); die();
            if (isset($data['client_id'])) {
                $client_id = $data['client_id'];
            } else {
                $client_id = "";

            }

            if (isset($data['member_id'])) {
                $member_id = $data['member_id'];
            } else {
                $member_id = "";
            }

            if (isset($data['created_date'])) {
                $created_at = $data['created_date'];
            } else {
                $created_at = "";
            }

            if (isset($data['priority'])) {
                $priority = $data['priority'];
            } else {
                $priority = "";
            }

            if (isset($data['task_status'])) {
                $task_status = $data['task_status'];
            } else {
                $task_status = "";
            }

            if (isset($data['$task_type'])) {
                $task_type = $data['$task_type'];
            } else {
                $task_type = "";
            }

            $whereArr = array();
            if ($client_id != "") {
                array_push($whereArr, "client_id = {$client_id}");
            }
            if ($member_id != "") {
                array_push($whereArr, "member_id = {$member_id}");
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
              tasks.action_date,
              tasks.client_id,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id  WHERE  ( (status != 1 and date(tasks.created_at) <= CURDATE())  or (status = 1 AND date(tasks.action_date) = CURDATE() )) and  {$whereStr} order by task_id DESC ";

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
              tasks.action_date,
              tasks.client_id,
              tasks.site_url,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id where ( (status != 1 and status != 6 and date(tasks.created_at) <= CURDATE())  or (status = 1 AND date(tasks.action_date) = CURDATE() )) order by task_id DESC ";

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
              tasks.action_date,
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
              tasks.action_date,
              tasks.site_url,
              tasks.priority,
              member.username as membername,
              concat(member.first_name , ' ', member.last_name ) as members_full_name
              FROM `tasks` 
              left join users on tasks.user_id = users.id 
              left join users as member on tasks.member_id =member.id where ( (status != 1 and date(tasks.created_at) <= CURDATE())  or (status = 1 AND date(tasks.action_date) = CURDATE() ))
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
              tasks.action_date,
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
              tasks.action_date,
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
              tasks.site_username,
              tasks.site_password,
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


    public function getTaskById($id)
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
              tasks.action_date,
              tasks.site_url,
              tasks.status,
              tasks.site_username,
              tasks.site_password,
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

            $sql = "UPDATE tasks SET status = :status, action_date = :action_date WHERE  id ='$taskID'";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':action_date', date('Y-m-d h:s:i'));
            $stmt->execute();


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
            $sql = "UPDATE  tasks SET status = :status , site_url = :site_url ,  site_username = :site_username ,   action_date = :action_date , site_password = :site_password   WHERE  id ='$taskID'";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':site_url', $data['site_url']);
            $stmt->bindParam(':site_username', $data['site_username']);
            $stmt->bindParam(':site_password', $data['site_password']);
            $stmt->bindParam(':action_date', date('Y-m-d h:s:i'));

            $stmt->execute();

            return $taskID;

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function editTask($data)
    {
        $taskID = $data['task_id'];
        //var_dump($data);die();
        try {

            //$id = $data['id'];
            //var_dump($id); die();
            $stmt = $this->db->prepare("update tasks set client_id=:client_id,description=:description,task_type=:task_type, member_id=:member_id, priority=:priority, status=:status, action_date =:action_date, site_username=:site_username, site_password=:site_password where id='$taskID'");
            $stmt->bindParam(':client_id', $data['client_id']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':task_type', $data['task_type']);
            $stmt->bindParam(':member_id', $data['member_id']);
            $stmt->bindParam(':priority', $data['priority']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':action_date', date('Y-m-d h:s:i'));
            $stmt->bindParam(':site_username', $data['site_username']);
            $stmt->bindParam(':site_password', $data['site_password']);
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

    public function getAdminTaskCount()
    {
        $sql = "SELECT 
      COUNT(*) AS all_task,
       SUM(CASE 
             WHEN  (status != 1 and status != 6 and date(tasks.created_at) <= CURDATE())  or (status = 1 AND date(tasks.action_date) = CURDATE() ) then 1
             ELSE 0
           END) AS today_task,
       SUM(CASE 
             WHEN status = 1 THEN 1
             ELSE 0
           END) AS complete_task, 
       SUM(CASE 
             WHEN status = 3 THEN 1
             ELSE 0
           END) AS pending_task,
       SUM(CASE 
             WHEN status = 4 THEN 1
             ELSE 0
           END) AS done_task,
       SUM(CASE 
             WHEN status = 5 THEN 1
             ELSE 0
           END) AS on_progress_task,
       SUM(CASE 
             WHEN status = 6 THEN 1
             ELSE 0
           END) AS pause_task,
       SUM(CASE 
             WHEN status = 1 AND date(created_at) = CURDATE() THEN 1
             ELSE 0
           END) AS  today_complete_task,
       SUM(CASE 
             WHEN status != 1  THEN 1
             ELSE 0
           END) AS today_assigned_task,

        SUM(CASE 
             WHEN status = 4 AND date(created_at) = CURDATE() THEN 1
             ELSE 0
           END) AS today_done_task,
       SUM(CASE 
             WHEN status = 5 AND date(created_at) = CURDATE()  THEN 1
             ELSE 0
           END) AS today_on_progress_task,

        SUM(CASE 
             WHEN status = 3 AND date(created_at) = CURDATE() THEN 1
             ELSE 0
           END) AS today_pending_task,
          SUM(CASE 
             WHEN status = 6 AND date(created_at) = CURDATE() THEN 1
             ELSE 0
           END) AS today_pause_task       
      FROM tasks";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetch();

        return $res;
    }

    public function getMemberTodayTaskCount()
    {
        $member_id = $_SESSION['user'][0]['id'];
        $sql = "SELECT 
      COUNT(*) AS all_task,
       SUM(CASE 
             WHEN  (status != 1 and date(tasks.created_at) <= CURDATE())  or (status = 1 AND date(tasks.action_date) = CURDATE() ) then 1
             ELSE 0
           END) AS today_task,
       SUM(CASE 
             WHEN status = 1 THEN 1
             ELSE 0
           END) AS complete_task, 
       SUM(CASE 
             WHEN status = 3 THEN 1
             ELSE 0
           END) AS pending_task,
       SUM(CASE 
             WHEN status = 4 THEN 1
             ELSE 0
           END) AS done_task,
       SUM(CASE 
             WHEN status = 5 THEN 1
             ELSE 0
           END) AS on_progress_task,
       SUM(CASE 
             WHEN status = 6 THEN 1
             ELSE 0
           END) AS pause_task,
       SUM(CASE 
             WHEN status = 1 AND date(created_at) = CURDATE() THEN 1
             ELSE 0
           END) AS today_complete_task,
        SUM(CASE 
             WHEN status = 4 AND date(created_at) = CURDATE() THEN 1
             ELSE 0
           END) AS today_done_task,
       SUM(CASE 
             WHEN status = 5 AND date(created_at) = CURDATE()  THEN 1
             ELSE 0
           END) AS today_on_progress_task,

        SUM(CASE 
             WHEN status = 3 AND date(created_at) = CURDATE() THEN 1
             ELSE 0
           END) AS today_pending_task,
          SUM(CASE 
             WHEN status = 6 AND date(created_at) = CURDATE() THEN 1
             ELSE 0
           END) AS today_pause_task       
      FROM tasks WHERE member_id = '$member_id' ";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetch();

        return $res;
    }

    public function getBarcharTask(){

       $sql = "SELECT COUNT(id) as totalTaskId, date(created_at) as created_day FROM `tasks` GROUP BY created_day desc";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetchAll();

        return $result;

    }

    public function getBarcharTaskMember()
    {

        $member_id = $_SESSION['user'][0]['id'];

        $sql = "SELECT COUNT(id) as totalTaskId, date(created_at) as created_day FROM `tasks` where member_id = '$member_id' GROUP BY created_day desc";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetchAll();

        return $result;

    }

    public function getMemberTaskCount($userID)
    {
        $sql = "SELECT 
      COUNT(*) AS all_task,
       SUM(CASE 
             WHEN status != 1  THEN 1
             ELSE 0
           END) AS today_task,
       SUM(CASE 
             WHEN status = 1 THEN 1
             ELSE 0
           END) AS complete_task, 
       SUM(CASE 
             WHEN status = 3 THEN 1
             ELSE 0
           END) AS pending_task 
      FROM tasks WHERE member_id = $userID";
        //var_dump($sql); die();
        $stmt = $this->db->query($sql);
        $res = $stmt->fetch();

        return $res;
    }

    public function getToadyTaskCount()
    {
        $sql = "SELECT 
      COUNT(*) AS all_task,
       SUM(CASE 
             when ( (status != 1 and date(tasks.created_at) <= CURDATE())  or (status = 1 AND date(tasks.action_date) = CURDATE() )) then 1
             ELSE 0
           END) AS today_task,
       SUM(CASE 
             WHEN status = 1 AND date(created_at) = CURDATE() THEN 1
             ELSE 0
           END) AS today_complete_task, 
       SUM(CASE 
             WHEN status = 3 AND date(created_at) = CURDATE() THEN 1
             ELSE 0
           END) AS today_pending_task 
      FROM tasks";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetch();

        return $res;
    }


    public function topUserbyTask()
    {
        $topUser = "SELECT COUNT(t.id) as total_task, t.member_id, u.username, u.id FROM tasks t, users u where u.id = t.member_id and t.status = 1 group by t.member_id order by total_task desc";
        $topAllUser = $this->db->query($topUser);
        $getAllUser = $topAllUser->fetchAll();
        return $getAllUser;
    }



}