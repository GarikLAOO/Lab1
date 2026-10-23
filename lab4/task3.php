<?php 
namespace Task3;

trait A { 
    function a() { echo "Hello "; } 
}  
trait B { 
    function b() { echo "world"; } 
}  
class Test {       
    use A, B; 
    function c() { echo "!"; } 
} 

$t = new Test(); 
$t->a(); 
$t->b(); 
$t->c(); 
echo "<br><hr>";
?>