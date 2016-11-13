<?php
namespace App;

class UserMapper extends Mapper
{
    public function checkUser($data)
    {
        $username = $data['username'];
        $password = sha1($data['password']);
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetchAll();

        return $res;
    }

    public function checkUserName($data)
    {
        $username = $data['username'];
        $sql = "SELECT username FROM users WHERE username='$username'";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetch();
        if (!empty($res)) {
            return true;
        } else {
            return false;
        }
    }

    public function createUser($data)
    {
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $username = $data['username'];
        $password = sha1($data['password']);
        $role = $data['role'];
        $salck_username = $data['salck_username'];


        try {
            $stmt = $this->db->prepare("INSERT INTO users(
                  first_name,
                  last_name,
                  username,
                  password,
                  role,
                  slack_username
                )VALUES (
                  :first_name,
                  :last_name,
                  :username,
                  :password,
                  :role,
                  :salck_username
                )
                ");

            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':salck_username', $salck_username);
            $stmt->execute();

            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw $e;
            //return false
        }
    }


    public function memberList()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetchAll();

        return $res;

    }

    public function memberTaskList($data)
    {
        $member_id=$data['member_id'];
        //var_dump($member_id); die();
        $sql = "SELECT * FROM tasks where member_id='$member_id'";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetchAll();

        return $res;

    }



    public function memberListById($id)
    {
        $sql = "SELECT * FROM users WHERE id='$id'";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetch();

        return $res;
    }

    public function memberEdit($data)
    {
        $id = $data['id'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $username = $data['username'];
        $role = $data['role'];
        $slack_username = $data['salck_username'];
        try {

            $stmt = $this->db->prepare("UPDATE users SET
                  first_name = :first_name,
                  last_name = :last_name,
                  username = :username,
                  slack_username = :salck_username,
                  role = :role WHERE id = :id
                ");
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':salck_username',$slack_username);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function memberDelete($id)
    {
        $sql = "DELETE FROM users WHERE id='$id' LIMIT 1";
        $res = $this->db->query($sql);

        return $res;
    }

    public function userDetails()
    {
        $user_id = $_SESSION['user'][0]['id'];
        $userData = "select * from users where id = '$user_id'";

        $userDetails = $this->db->query($userData);
        return $userDetails;
    }
}