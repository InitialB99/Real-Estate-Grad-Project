<?php
require_once 'classes/user.php';

class Checks
{

    private $error = "";

    public function check_client($id)
    {

        if (!isset($id) || !is_numeric($id)){
            $this->error .= 'No sessionid!';
        }

        $user = new User();
        $user_data = $user->get_data($id);

        if(!$user_data)
        {
            $this->error = 'No data!';
        }
                
            if($user_data['access'] !== 0)
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

    public function check_agent($id)
    {

        if (!isset($id) || !is_numeric($id)){
            $this->error .= 'No sessionid!';
        }

        $user = new User();
        $user_data = $user->get_data($id);

        if(!$user_data)
        {
            $this->error = 'No data!';
        }
                
            if($user_data['access'] !== 1)
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
}