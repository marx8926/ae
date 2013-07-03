<?php

class File_Streamer
{
	private $fileName;
	private $contentLength;
	private $path;
	
	public function __construct()
	{
        if (array_key_exists('HTTP_X_FILE_NAME', $_SERVER) && array_key_exists('CONTENT_LENGTH', $_SERVER)) {
            $this->fileName = $_SERVER['HTTP_X_FILE_NAME'];
            $this->contentLength = $_SERVER['CONTENT_LENGTH'];
        } else throw new Exception("Error retrieving headers");
	}
    
    public function setDestination($p)
    {
    	$this->path = $p;
    }
    
    public function receive()
    {
        if (!$this->contentLength > 0) {
            throw new Exception('No file uploaded!');
        }
		$this->fileName = date("Y-m-d")."-".$this->fileName;
        file_put_contents(
            $this->path .$this->fileName,
            file_get_contents("php://input")
        );
        
        return true;
    }
}
