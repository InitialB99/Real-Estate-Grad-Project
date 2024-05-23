<?php

class User
{
    public function get_data($id)
    {
        $query = "select * from users where sessionid = ? limit 1";
        $params = [$id];

        $db = new Database();
        $result = $db->read($query, $params);

        if($result)
        {
            $row = $result[0];
            return $row;
        }else
        {
            return false;
        }
    }

}