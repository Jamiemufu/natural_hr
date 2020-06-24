<?php

class Validate
{
    private $_errors = array();
    private $_passed = false;

    public function __construct()
    {
        $this->db = new Database();
        $this->db = $this->db->returnConnection();
    }

    /**
     * Check if email address is already registered
     *
     * @param [type] $value
     * @return void
     */
    public function checkEmailExsists($value)
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE email = :uemail');
        $statement->execute(['uemail' => $value]);
        if ($statement->rowCount() > 0) {
            return false;
        }
    }

    /**
     * isValid - check $_POST vars
     *
     * will return either passed or errors
     *
     * @param [type] $inputs
     * @param array $items
     * @return boolean
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
                if ($rule === 'unique' && $rule === 'required') {
                    if (!$this->checkEmailExsists($value)) {
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
     * @return void
     */
    public function errors()
    {
        return $this->_errors;
    }

    /**
     * Return passed
     *
     * @return void
     */
    public function valid()
    {
        return $this->_passed;
    }

}
