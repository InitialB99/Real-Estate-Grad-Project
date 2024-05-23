<?php

class Login
{
    
    private $error = "";

    public function evaluate($data)
    {
        $email = addsLashes($data["email"]);
        $password = addsLashes($data["password"]);

        $query = "select * from users where email = ? limit 1";
        $params = [$email];

        $DB = new Database();
        $result = $DB->read($query, $params);
        
        if($result)
        {
            $row = $result[0];

            if($password == $row['password'])
            {
                //create session data
                $_SESSION['realestate_sessionid'] = $row['sessionid'];

            }else
            {
                $this->error .= "Wrong password<br>";
            }
        }else
        {
            $this->error .= "No email found<br>";
        }
        
        return $this->error;
        
    }

    public function check_login($id)
    {
        $query = "select id from users where sessionid = ? limit 1";
        $params = [$id];

        $DB = new Database();
        $result = $DB->read($query, $params);

        if($result){
            return true;
        }
        return false;
    }

}