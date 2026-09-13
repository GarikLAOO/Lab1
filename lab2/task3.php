<?php  
class A { 
  public static function test() { 
    echo 1; 
  } 

  public static function get() { 

    static::test(); 
  } 
}  

class B extends A { 
  public static function test() { 
    echo 2; 
  } 
} 

B::get();  
?>