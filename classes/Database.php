<?php

//start session on db instance
if (!isset($_SESSION)) {
    session_start();
}

require_once 'config.php';

class Database
{
    private $host = DB_SERVER;
    private $user = DB_USERNAME;
    private $db = DB_DATABASE;
    private $pass = DB_PASSWORD;
    private $table = DB_TABLE;
    private $conn;

    public function __construct()
    {

        $this->conn = new PDO("mysql:host=$this->host", $this->user, $this->pass);
        /**
         * This will create a new database and table is one doesnt exsist
         * This is only for ease of setting up for you - I would never ever do this otherwise, saves you copy and pasting sql
         */
        $this->createDatabase();
        // use the newly created database
        $this->conn->exec("use $this->db");
        //will only create table if already exsist
        $this->createTable();
        //enable try/catch
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * create a new database
     *
     * @return void
     */
    public function createDatabase()
    {
        try {
            $this->conn->exec("CREATE DATABASE IF NOT EXISTS $this->db");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }

    /**
     * create a users table
     *
     * @return void
     */
    public function createTable()
    {
        // create table if doesnt exsist
        $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                firstname VARCHAR(255) NOT NULL,
                lastname VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE
                )";

        // use exec() because no results are returned
        try {
            $this->conn->exec($sql);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }

    /**
     * Return connection
     *
     * @return void
     */
    public function returnConnection()
    {
        return $this->conn;
    }
}
