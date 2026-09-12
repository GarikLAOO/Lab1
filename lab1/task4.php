<?php  
    
    class Coor{

    private $text;
    private $login;
    private $password;

    function __construct($text, $login, $password) { 
        $this->text = $text; 
        $this->login = $login;
        $this->password = $password;
    } 

    function Getname() { 
        echo "<p>Name: " . $this->text . " | Login: " . $this->login . " | Password: " . $this->password . "</p>";
    } 

    function __destruct() {
        echo "<p>Object deleted: " . $this->text . "</p>";
    }
} 

    $object1 = new Coor("Nick", "nick_admin", "qwerty1234"); 
    $object2 = new Coor("Anna", "anna_user", "pass9876"); 
    $object3 = new Coor("John", "john_dev", "super_secret"); 

    $object1->Getname(); 
    $object2->Getname(); 
    $object3->Getname();

    /*if (isset($object)) {
        unset($object);
        echo "<p>Object deleted</p>";
    }*/
?>