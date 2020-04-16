<?php

class DbOperations
{
    private $conn;

    function __construct()
    {
        require_once dirname(__FILE__) . '/db_connect.php';

        $db = new DbConnect;
        $this->conn = $db->connect();
    }

    public function createUser($email, $password, $name, $school)
    {
        if (!$this->isEmailExists($email)) {
            $statement = $this->conn->prepare("INSERT INTO users (email,password,name,school) VALUES (?,?,?,?)");
            $statement->bind_param("ssss", $email, $password, $name, $school);

            if ($statement->execute()) {
                return USER_CREATED;
            } else {
                return USER_FAILURE;
            }
        } else {
            return USER_EXIST;
        }
    }

    private function isEmailExists($email)
    {
        $statement = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $statement->bind_param("s", $email);

        $statement->execute();
        $statement->store_result();

        return $statement->num_rows > 0;
    }
}
