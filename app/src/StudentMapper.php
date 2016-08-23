<?php
namespace App;



class StudentMapper extends Mapper
{
    public function getStudent() {
        $sql = "Select * from student";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}