<?php

// error that is thrown when an account error occurs (such as as an issue with the username or password)
class AccountException extends PDOException
{
}

class Account
{

    // these are private because they should not be accessed directly
    private $_id;
    private $_username;
    private $_password;
    private $_age;

    // a constructor is a special method that is called when an object is created
    public function __construct($id, $username, $password, $age)
    {
        $this->setId($id);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setAge($age);
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        // if the id is not null and is not a number or is less than or equal to 0 or is greater than 9223372036854775807 or if the id is already set
        // this large number is used because in my databasem, the id is of type BIGINT which corresponds to this number
        // this means that if my id is not null and is not a number or is less than or equal to 0 or is greater than 9223372036854775807 or if the id is already set, then an error is thrown
        if (
            $id !== null && (!is_numeric($id)
                || $id <= 0
                || $id > 9223372036854775807)
            || $this->_id !== null
        ) {
            throw new AccountException('Task ID error');
        }
        $this->_id = $id;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function setUsername($username)
    {
        // if the username is not null and is not a string or is less than 0 or is greater than 255, then an error is thrown
        if (strlen($username) <= 0 || strlen($username) > 255) {
            throw new AccountException('Username cannot be empty or more than 255 characters');
        }
        $this->_username = $username;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setPassword($password)
    {
        // Check if the password length is between 5 and 20 characters
        if (strlen($password) < 5 || strlen($password) > 20) {
            throw new AccountException('Password length error');
        }

        // Check if the password contains at least one special character, one capital letter, and one number
        if (
            !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password) ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[0-9]/', $password)
        ) {
            throw new AccountException('Password must include at least one special character, one capital letter, and one number');
        }

        $this->_password = $password;
    }

    public function getAge()
    {
        return $this->_age;
    }

    public function setAge($age)
    {
        $cage = intval($age);
        // throw new AccountException($cage);
        if ($cage < 13 || $cage > 120) {
            throw new AccountException('Age must be between 13 and 120');
        }
        $this->_age = $cage;
    }
}
