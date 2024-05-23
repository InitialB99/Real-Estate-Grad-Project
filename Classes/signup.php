<?php
class Signup
{
    
    private $error = "";
    public function evaluate($data)
    {
        foreach ($data as $key => $value) 
        {
            if(empty($value))
            {
                $this->error = $this->error . $key . " is empty!<br>";
            }
                     // Check if passwords match
            if ($key == "password" && $data["password"] !== $data["password2"])
            {
                $this->error = $this->error . "Passwords do not match!";
            }
        }

        if($this->error == "")
        {
            //no error
            $this->create_user($data);
        }else
        {
            return $this->error;
        }
    }
    public function create_user($data)
    {
        $sessionid = $this->create_sessionid();
        $firstname = ucfirst($data["first_name"]);
        $lastname = ucfirst($data["last_name"]);
        $email = $data["email"];
        $password = $data["password"];

        $query = "insert into users 
        (sessionid,first_name,last_name,email,password) 
        values 
        (?,?,?,?,?)";
        $params = [$sessionid,$firstname,$lastname,$email,$password];

        $DB = new Database();
        $DB->save($query, $params);
    }

    private function create_sessionid()
    {

        $lenght = rand(4,19);
        $number = '';
        for ($i=0; $i < $lenght; $i++) { 

            $new_rand = rand(0,9);

            $number = $number . $new_rand;
        }

        return $number;

    }
      
}