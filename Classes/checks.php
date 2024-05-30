<?php
include_once 'Classes/user.php';

class Checks
{

    private $error = "";

    private function check_user($id, $access_level)
    {

        $user = new User();
        $user_data = $user->get_data($id);

        if(!$user_data)
        {
            $this->error = 'No data!';
        }
                
        if($user_data['access'] !== $access_level && $user_data['access'] !== 2)
        {
            $this->error .= 'Wrong access!';
        }
        

        if ($this->error == "") {
            // no error
            return $user_data;
        }else {
            return false;
        }
    }

    public function check_client($id)
    {
        return $this->check_user($id, 0);
    }

    public function check_agent($id)
    {

        return $this->check_user($id, 1);
    }
    
    public function check_admin($id) {
        
        return $this->check_user($id, 2);
    }
    
}