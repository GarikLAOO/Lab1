<?php 
trait my_first_trait { 
    public function traitFunction() { 
        echo "Hello world<br>"; 
    }
    
    public function timeGreeting() {
        $hour = (int)date('H');
        if ($hour >= 5 && $hour < 12) {
            echo "Good morning<br>";
        } elseif ($hour >= 12 && $hour < 18) {
            echo "Good day<br>";
        } else {
            echo "Good evening<br>";
        }
    }
} 

class HelloWorld { 
    use my_first_trait;
} 

$objTest = new HelloWorld(); 
$objTest->traitFunction(); 
$objTest->timeGreeting();
echo "<hr>";
?>