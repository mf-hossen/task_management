<?php
    namespace App;
    class UserMapper extends Mapper
    {
        public  function checkUser($data)
        {
            $username = $data['username'];
            $password = $data['password'];
            $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $stmt = $this->db->query($sql);
            $res= $stmt->fetchAll();
            return $res;
        }

    }