<?php 
    class Engine {
        public function start() {
            echo "Engine started.<br>";
        }
    }

    class Car{
        
        protected static $totalVehicles = 0;

        protected $engine;
        protected $price;
        protected $brand;

        public function __construct($brand, $price) {
            $this->engine = new Engine();
            $this->brand = $brand;
            $this->price = $price;

            self::$totalVehicles++;
        }

        public function start() {
            echo "Car is starting...<br>";
            $this->engine->start();
        }

        public function __destruct() {
            echo "Об'єкт автомобіль знищується.<br>";
        }

        public static function getTotalVehicles() {
            return self::$totalVehicles;
        }
    }

    class Truck extends Car {
        private $loadCapacity;

       public function __construct($brand, $price, $loadCapacity) {
            parent::__construct($brand, $price);
            $this->loadCapacity = $loadCapacity;
        }

        public function printInfo() {
            echo "<b>Вантажівка:</b> {$this->brand} | <b>Ціна:</b> {$this->price}$ | <b>Вантажопідйомність:</b> {$this->loadCapacity} кг<br>";
        }

        public function __destruct() {
            echo "Деструктор Truck: об'єкт вантажівки {$this->brand} знищується.<br>";
        }
    }

    echo "Кількість авто на початку: " . Car::getTotalVehicles() . "<br><br>";
    $car1 = new Car("Toyota", 25000);
    $car2 = new Car("BMW", 45000);
    $truck1 = new Truck("Volvo", 120000, 25000);
    $myTruck = new Truck("Volvo", 120000, 25000);
    
    $myTruck->printInfo();
    
    $myTruck->start();

    echo "<br>Кількість авто після створення: " . Car::getTotalVehicles() . "<br>";
?>