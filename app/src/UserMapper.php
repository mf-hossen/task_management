<?php
    namespace App;
    class UserMapper extends Mapper
    {
        public  function checkUser($data)
        {
            $username = $data['username'];
            $password = sha1($data['password']);
            $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $stmt = $this->db->query($sql);
            $res= $stmt->fetchAll();
            return $res;
        }

        public function createUser($data)
        {
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];
            $username = $data['username'];
            $password = sha1($data['password']);
            $role      = $data['role'];


            try{
                $stmt = $this->db->prepare("INSERT INTO users(
                  first_name,
                  last_name,
                  username,
                  password,
                  role
                )VALUES (
                  :first_name,
                  :last_name,
                  :username,
                  :password,
                  :role
                )
                ");

                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':role', $role);
                $stmt->execute();
                return $this->db->lastInsertId();
            }catch (Exception $e){
                throw $e;
                //return false
            }
        }



        public  function memberList()
        {
            $sql = "SELECT * FROM users";
            $stmt = $this->db->query($sql);
            $res= $stmt->fetchAll();
            return $res;

        }


        public function memberListById($id)
        {
            $sql = "SELECT * FROM users WHERE id='$id'";
            $stmt = $this->db->query($sql);
            $res= $stmt->fetchAll();
            return $res;
        }

        public function memberEdit($data)
        {
            $id = $data['id'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];
            $username = $data['username'];
            $role = $data['role'];

            try{

                $stmt = $this->db->prepare("UPDATE users SET
                  first_name = :first_name,
                  last_name = :last_name,
                  username = :username,
                  role = :role WHERE id = :id
                ");
                $stmt->bindParam(':first_name',$first_name);
                $stmt->bindParam(':last_name',$last_name);
                $stmt->bindParam(':username',$username);
                $stmt->bindParam(':role',$role);
                $stmt->bindParam(':id',$id);
                $stmt->execute();
                return true;
            }catch (Exception $e){
                throw $e;
            }
        }
    }