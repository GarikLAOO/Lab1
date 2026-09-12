<?php class Croor{

    private $name; 
    
    function Getname() 
    { 

    echo $this->name; 

    } 

    function Setname($text) 
    { 

    $this->name=$text; 

    } 

} 

$works=array();

$works[0]=new Croor();

$works[0]->Setname(" Nick ");

$works[1]=new Croor(); 

$works[1]->Setname(" Nick 1"); 

$works[2]=new Croor(); 

$works[2]->Setname(" Nick 2"); 

for($i=0;$i<3;$i++) {echo $works[$i]->Getname();}

?>