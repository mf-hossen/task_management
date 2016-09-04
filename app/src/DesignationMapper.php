<?php
namespace App;

class DesignationMapper extends Mapper
{
    public function getDesignation()
    {
        $sql = "SELECT * FROM designation";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetchAll();
        return $row;
    }
}
?>