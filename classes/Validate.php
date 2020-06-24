<?php

/**
 * Class Validate
 */
class Validate
{
    
    private $_errors = array();
    private $_passed = false;
    private $db;

    /**
     * Validate constructor.
     */
    public function __construct()
    {
        $this->db = new Database();
        $this->db = $this->db->returnConnection();
    }


    /**
     * Check is post vars are valid
     * Trim and strip
     *
     * @param $inputs
     * @param array $items
     * @return $this
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
                    if (!$this->checkEmailExists($value) === false) {
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
     * check if user email exists
     *
     * @param $value
     * @return bool
     */
    public function checkEmailExists($value)
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE email = :uemail');
        $statement->execute(['uemail' => $value]);
        if ($statement->rowCount() > 0) {
            return true;
        }
    }


    /**
     * Add error to errors
     *
     * @param $error
     */
    private function addError($error)
    {
        $this->_errors[] = $error;
    }


    /**
     * Return any errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->_errors;
    }


    /**
     * Return valid
     *
     * @return bool
     */
    public function valid()
    {
        return $this->_passed;
    }

}
