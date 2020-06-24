<?php

/**
 * Class Upload
 */
class Upload
{

    private $destinationPath;
    private $errorMessage;
    private $extensions;
    private $maxSize;
    private $uploadName;
    private $originalName;
    public $name = 'Upload';

    /**
     * @param $path
     */
    public function setDir($path)
    {
        $this->destinationPath = $path;
    }

    /**
     * @param $sizeMB
     */
    public function setMaxSize($sizeMB)
    {
        $this->maxSize = $sizeMB * (1024 * 1024);
    }

    /**
     * @param $options
     */
    public function setExtensions($options)
    {
        $this->extensions = $options;
    }

    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->errorMessage = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return mixed
     */
    public function getUploadName()
    {
        return $this->uploadName;
    }

    /**
     * @return mixed
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * @param $file
     * @param $username
     * @param $id
     * @return bool
     */
    public function uploadFile($file, $username, $id)
    {
        $result = false;
        $size = $_FILES[$file]["size"];
        $originalName = $_FILES[$file]["name"];
        $name = $username . $id . "_" . $_FILES[$file]["name"];
        $ext = pathinfo($name, PATHINFO_EXTENSION);

        $this->uploadName = $name;
        $this->originalName = $originalName;

        if (empty($name)) {
            $this->setMessage("File not selected");
        } else if ($size > $this->maxSize) {
            $this->setMessage("File too large");
        } else if (in_array($ext, $this->extensions)) {
            if (!is_dir($this->destinationPath))
                mkdir($this->destinationPath);

            if (file_exists($this->destinationPath . '/' . $this->uploadName))
                $this->setMessage("File already exists");
            else if (!is_writable($this->destinationPath . '/'))
                $this->setMessage("Destination is not writable");
            else {
                if (move_uploaded_file($_FILES[$file]["tmp_name"], $this->destinationPath . '/' . $this->uploadName)) {
                    $result = true;
                } else {
                    $this->setMessage("Upload failed, try later...");
                }
            }
        } else {
            $this->setMessage("Invalid file format");
        }
        return $result;
    }

}