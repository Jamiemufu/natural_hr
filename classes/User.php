<?php

include "Database.php";

/**
 * Class User
 */
class User
{
    /**
     * @var
     */
    protected $db;
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $username;
    /**
     * @var
     */
    private $password;
    /**
     * @var
     */
    private $firstname;
    /**
     * @var
     */
    private $lastname;
    /**
     * @var
     */
    private $email;
    /**
     * @var
     */
    private $uploads;


    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->db = new Database();
        $this->db = $this->db->returnConnection();
    }


    /**
     * @param $id
     */
    public function setID($id)
    {
        $this->id = $id;
    }


    /**
     * @param $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }


    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


    /**
     * @param $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }


    /**
     * @param $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }


    /**
     * @param $uploads
     */
    public function setUploads($uploads)
    {
        $this->uploads = $uploads;
    }


    /**
     * Hash password
     *
     * @param $password
     * @return false|string|null
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }


    /**
     * Check if user logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Logout
     */
    public function logout()
    {
        $_SESSION['login'] === false;
        unset($_SESSION);
        session_destroy();
        header("location:index.php");
    }


    /**
     * User Registration
     *
     * @return bool
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
     * User Login
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
     * get current user info
     *
     * @return mixed
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
     * Add user upload
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
