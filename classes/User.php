<?php

include "Database.php";

class User
{
    protected $db;
    private $id;
    private $username;
    private $password;
    private $firstname;
    private $lastname;
    private $email;

    public function __construct()
    {
        $this->db = new Database();
        $this->db = $this->db->returnConnection();
    }

    /**
     * set User ID
     *
     * @param [type] $id
     * @return void
     */
    public function setID($id)
    {
        $this->_id = $id;
    }

    /**
     * Set Username
     *
     * @param [type] $username
     * @return void
     */
    public function setUsername($username)
    {
        $this->_username = $username;
    }

    /**
     * Set User email
     *
     * @param [type] $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * Set user password and hash it
     *
     * @param [type] $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * Set user firstname
     *
     * @param [type] $firstname
     * @return void
     */
    public function setFirstname($firstname)
    {
        $this->_firstname = $firstname;
    }

    /**
     * Set User lastname
     *
     * @param [type] $lastname
     * @return void
     */
    public function setLastname($lastname)
    {
        $this->_lastname = $lastname;
    }

    /**
     * Verify Hash Method
     *
     * @param [type] $password
     * @param [type] $vpassword
     * @return void
     */
    public function verifyHash($password, $vpassword)
    {
        if (password_verify($password, $vpassword)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Hash password Method
     *
     * @param [type] $password
     * @return void
     */
    public function hashPassword($password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $hash;
    }

    /**
     * Check if user logged in
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
        if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
        $_SESSION['login'] === false;
        unset($_SESSION);
        session_destroy();
        header("location:index.php");
    }
    /**
     * User registration method
     *
     * Check for exsisting users email address, or save new user
     *
     * @return void
     */
    public function userRegistration()
    {

        try {
            // check if user already exsists via unique email address
            $password = $this->hashPassword($this->_password);
            $statement = $this->db->prepare('SELECT * FROM users WHERE email = :uemail');
            $statement->execute(['uemail' => $this->_email]);
            // email already exsists
            if ($statement->rowCount() > 0) {
                return false;
            } else {
                $statement = $this->db->prepare('INSERT INTO users (username, password, firstname, lastname, email) VALUES (:uname, :pword, :fname, :lname, :uemail)');

                $statement->execute([
                    'uname' => $this->_username,
                    // save the hashed password
                    'pword' => $password,
                    'fname' => $this->_firstname,
                    'lname' => $this->_lastname,
                    'uemail' => $this->_email,
                ]);
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * User Login method
     *
     * @return void
     */
    public function login()
    {
        try {
            $statement = $this->db->prepare('SELECT * FROM users where email = :email');
            $statement->execute([
                'email' => $this->_email,
            ]);

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

                if (password_verify($this->_password, $row["password"])) {
                    $_SESSION['login'] = true;
                    $_SESSION['id'] = $row['id'];
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Get current user info
     *
     * @return void
     */
    public function getInfo()
    {
        try {
            $statement = $this->db->prepare('SELECT * FROM users where id = :id');
            $statement->execute(['id' => $this->_id]);
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

                return $row;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
