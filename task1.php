<?php  class Coor{  

private $name;  

function Getname()  {  

return $this->name; 

}  

function Setname($text)  

{  

    $this->name=$text;  

}  

}  

$object = new Coor;  

$object->Setname("Nick"); 

echo $object->Getname();  ?> 