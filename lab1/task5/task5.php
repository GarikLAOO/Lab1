<?php  
class WorkWithFile { 
    
    public $buff; 
    public $filename; 

    function __construct($filename) 
    { 
        $uploaddir = './'; 

        $this->filename = $uploaddir .$filename; 

        if(!file_exists($this->filename)) exit("File does not exist"); 

        $fd = fopen($this->filename, "r"); 
        if(!$fd) exit("File open error"); 

        $size = filesize($this->filename);
        
        if ($size > 0) {
            $this->buff = fread($fd, $size); 
        } else {
            $this->buff = "";
        }
        
        fclose($fd) ; 

    } 

    function getContent()
    { 
        return $this->buff; 
    } 

    function getsize() 
    { 
        return filesize($this->filename); 
    } 

    function getCount() { 

        if(!empty($this->filename)) 
        { 

            $arr = file($this->filename); 
            return count($arr); 

        } else return 0; 

        } 

    } 


$first = new WorkWithFile("count.txt"); 
echo "{$first->getContent()}<br>"; 
echo "{$first->getsize()}<br>"; 
echo "{$first->getCount()}<br>"; 


?> 