<?php
namespace App;

class MemberMapper extends Mapper
{
    public function getMember()
    {
        $sql = "SELECT * FROM users where role='member'";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetchAll();
        return $row;
    }
}
?>