<?php

class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "Imobiliare";

    private function connect()
    {
        // Establish the database connection
        $connection = new mysqli($this->host, $this->username, $this->password, $this->db);
        if ($connection->connect_error) {
            die('Database connection failed: ' . $connection->connect_error);
        }
        return $connection;
    }

    function read($query, $params = [])
    {
        // Execute the SELECT query
        $conn = $this->connect();
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            return 'Prepare failed: ' . $conn->error;
        } else {

            $this->bindParams($stmt, $params);
            $stmt->execute();
            $result = $stmt->get_result();

        if ($result === false) {
            return 'Get result failed: ' . $stmt->error;
        } 
        
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $stmt->close();
            $conn->close();

            return $data;
        }
    }

    function save($query, $params = [])
    {
        // Execute the INSERT/UPDATE/DELETE query
        $conn = $this->connect();
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die('Prepare failed: ' . $conn->error);
        } else{

            $this->bindParams($stmt, $params);

            $result = $stmt->execute();

            if ($result === false) {
                die('Execute failed: ' . $stmt->error);
            } else{

            $stmt->close();
            $conn->close();

            return true;
            }
        }
    }

    private function bindParams($stmt, $params) {
        if (!empty($params)) {
            // Dynamically build the types string
            $types = '';
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_string($param)) {
                    $types .= 's';
                } else {
                    $types .= 'b'; // blob and unknown types
                }
            }
            // Bind the parameters
            $stmt->bind_param($types, ...$params);
        }
    }

}