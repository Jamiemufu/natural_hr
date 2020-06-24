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
    private $uploads;

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
        $this->id = $id;
    }

    /**
     * Set Username
     *
     * @param [type] $username
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Set User email
     *
     * @param [type] $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Set user password and hash it
     *
     * @param [type] $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Set user firstname
     *
     * @param [type] $firstname
     * @return void
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Set User lastname
     *
     * @param [type] $lastname
     * @return void
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Set user uploads
     *
     * @param $uploads
     * @retern void
     */
    public function setUploads($uploads)
    {
        $this->uploads = $uploads;
    }

    /**
     * Verify Hash Method
     *
     * @param $password
     * @param $vpassword
     * @return bool
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
     * @return string
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
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
            $password = $this->hashPassword($this->password);
            $statement = $this->db->prepare('SELECT * FROM users WHERE email = :uemail');
            $statement->execute(['uemail' => $this->_email]);
            // email already exsists
            if ($statement->rowCount() > 0) {
                return false;
            } else {
                $statement = $this->db->prepare('INSERT INTO users (username, password, firstname, lastname, email) VALUES (:uname, :pword, :fname, :lname, :uemail)');

                $statement->execute([
                    'uname' => $this->username,
                    // save the hashed password
                    'pword' => $password,
                    'fname' => $this->firstname,
                    'lname' => $this->lastname,
                    'uemail' => $this->email,
                ]);
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * User Login method
     *
     * @return bool
     */
    public function login()
    {
        try {
            $statement = $this->db->prepare('SELECT * FROM users where email = :email');
            $statement->execute([
                'email' => $this->email,
            ]);

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

                if (password_verify($this->password, $row["password"])) {
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
     * @return array
     */
    public function getInfo()
    {
        try {
            $statement = $this->db->prepare('SELECT * FROM users where id = :id');
            $statement->execute(['id' => $this->id]);
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                return $row;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * add user upload
     *
     * @return void
     */
    public function addUpload()
    {
        try {
            $statement = $this->db->prepare('UPDATE users SET uploads = :uploads WHERE id = :id');
            $statement->execute([
                'uploads' => $this->uploads,
                'id' => $this->id,
            ]);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
