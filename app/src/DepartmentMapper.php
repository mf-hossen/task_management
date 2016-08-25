<?php
namespace App;

class DepartmentMapper extends Mapper
{
    public function getDepartment()
    {
        $sql = "SELECT * FROM department";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetchAll();
        return $row;
    }
}
?>