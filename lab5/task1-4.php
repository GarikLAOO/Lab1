<?php
// Завдання 1 та 2: Singleton через трейт
trait SingletonTrait {
    protected static $instance = null;

    protected function __construct() {}
    protected function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}

class SomeClass {
    use SingletonTrait;
    public $value;
}

$obj1 = SomeClass::getInstance();
$obj1->value = "Test";
$obj2 = SomeClass::getInstance();
// $obj3 = new SomeClass(); // Викличе фатальну помилку (protected constructor)
echo "Об'єкти ідентичні: " . ($obj1 === $obj2 ? 'Так' : 'Ні') . "<br>";

// Завдання 3: Singleton для бази даних
class Database {
    use SingletonTrait;
    
    public $db;

    protected function __construct() {
        echo "<h1>Connecting with database</h1>";
        $this->db = new mysqli('localhost','root','');
    }
}

// Повідомлення про підключення виведеться лише один раз
$db1 = Database::getInstance();
$db2 = Database::getInstance();
$db3 = Database::getInstance();
?>