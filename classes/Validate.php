<?php

class Validate
{
    private $_errors = array();
    private $_passed = false;

    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->db = $this->db->returnConnection();
    }

    /**
     * isValid - check $_POST vars
     *
     * will return either passed or errors
     *
     * @param [type] $inputs
     * @param array $items
     * @return Validate
     */
    public function isValid($inputs, $items = array())
    {
        foreach ($items as $item => $rules) {
            $item = trim($item);
            $item = strip_tags($item);

            foreach ($rules as $rule => $v) {

                $value = trim($inputs[$item]);

                // check if required
                if ($rule === 'required' && strlen($value) === 0) {
                    //This is required and needs an error
                    $this->addError("{$item} is required");
                }
                // check if already registered
                if ($rule === 'unique') {
                    if (!$this->checkEmailExsists($value) === false) {
                        $this->addError("{$item} is already registered");
                    };
                }
                // check for matching passwords
                if ($rule === 'match') {
                    if ($value !== $inputs['password']) {
                        $this->addError("passwords need to match");
                    }
                }
            }
        }

        if (empty($this->_errors)) {
            $this->_passed = true;
        }

        return $this;
    }

    /**
     * Check if email address is already registered
     *
     * @param [type] $value
     * @return bool
     */
    public function checkEmailExsists($value)
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE email = :uemail');
        $statement->execute(['uemail' => $value]);
        if ($statement->rowCount() > 0) {
            return true;
        }
    }

    /**
     * Add errors
     *
     * @param [type] $error
     * @return void
     */
    private function addError($error)
    {
        $this->_errors[] = $error;
    }

    /**
     * Return all errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->_errors;
    }

    /**
     * Return passed
     *
     * @return bool
     */
    public function valid()
    {
        return $this->_passed;
    }

}
