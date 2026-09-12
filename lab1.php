<?php  class Coor{  //creating Coor class

private $name;  //private variable name

function Getname()  {  //function for getting “name” variable

return $this->name; //function return value of “name” variable

}  

function Setname($text)  //function for setting “name” variable

{  

    $this->name=$text;  //setting “name” by “text” 

}  

}  

$object = new Coor;  //creating new object of “Coor” class

$object->Setname("Nick"); //setting “name”

echo $object->Getname();  ?>  //printing object name