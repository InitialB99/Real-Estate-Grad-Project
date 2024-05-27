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

    public function update_user_data($id, $new_data) {
        $db = new Database();

        // Fetch current user data
        $current_data = $this->get_data($id);
        if (!$current_data) {
            return false; // User not found
        }

        // Update fields only if new data is provided
        $first_name = $new_data['first_name'] ?? $current_data['first_name'];
        $last_name = $new_data['last_name'] ?? $current_data['last_name'];
        $email = $new_data['email'] ?? $current_data['email'];
        $password = $new_data['password'] ?? $current_data['password'];
        $number = $new_data['number'] ?? $current_data['number'];

        // Prepare and execute the update query
        $query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ?, number = ? WHERE id = ?";
        $params = [$first_name, $last_name, $email, $password, $number, $current_data['id']];

        $result = $db->save($query, $params);
        if($result){
            return true;
        } else {
            return false;
        }
    }
}
