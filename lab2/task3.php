<?php  
class A { 
  public function test() { 
    echo 1; 
  } 

  public function get() { 

    // Виведе 2 (доступ до похідного класу B через пізнє статичне зв'язування)
    static::test(); 
  } 
}  

class B extends A { 
  public function test() { 
    echo 2; 
  } 
} 

$b = new B();
$b->get();  
?>