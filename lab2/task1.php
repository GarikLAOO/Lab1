<?php  class A {  

    function example() {  
        echo "This is parent function A::example().<br>";
    }}  

class B extends A {       
    
    function example() {  
      echo  "This  is  overriden  function B::example().<br>";  
      parent::example();  
    }}  

$b = new B; 
$b->example();
?>  