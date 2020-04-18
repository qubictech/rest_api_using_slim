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

    public function getUsers()
    {
        $statement = $this->conn->prepare("SELECT id,email,name,school FROM users");
        $statement->execute();
        $statement->bind_result($id, $email, $name, $school);

        $users = array();

        while ($statement->fetch()) {
            $user = array();

            $user['id'] = $id;
            $user['email'] = $email;
            $user['name'] = $name;
            $user['school'] = $school;

            array_push($users, $user);
        }

        return $users;
    }

    public function getUserById($id)
    {
        $statement = $this->conn->prepare("SELECT id,name,email,school FROM users WHERE id = ?");
        $statement->bind_param('i', $id);

        $statement->execute();
        $statement->bind_result($id, $name, $email, $school);

        $statement->fetch();

        $user = array();

        $user['id'] = $id;
        $user['email'] = $email;
        $user['name'] = $name;
        $user['school'] = $school;

        return $user;
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
