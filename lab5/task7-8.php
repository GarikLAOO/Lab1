<?php
// Завдання 7: Ієрархія Vehicle та Factory
abstract class Vehicle {
    protected $country, $brand, $year;
    public function __construct($country, $brand, $year) {
        $this->country = $country; $this->brand = $brand; $this->year = $year;
    }
    abstract public function getDetails();
}

class Car extends Vehicle {
    private $engine, $power, $color;
    public function __construct($country, $brand, $year, $engine, $power, $color) {
        parent::__construct($country, $brand, $year);
        $this->engine = $engine; $this->power = $power; $this->color = $color;
    }
    public function getDetails() { return "Автомобіль: $this->brand ($this->color)"; }
}

class Bike extends Vehicle {
    private $weight, $type, $wheelDiameter;
    public function __construct($country, $brand, $year, $weight, $type, $diameter) {
        parent::__construct($country, $brand, $year);
        $this->weight = $weight; $this->type = $type; $this->wheelDiameter = $diameter;
    }
    public function getDetails() { return "Велосипед: $this->brand ($this->type)"; }
}

class Motorcycle extends Vehicle {
    private $engine, $color, $type;
    public function __construct($country, $brand, $year, $engine, $color, $type) {
        parent::__construct($country, $brand, $year);
        $this->engine = $engine; $this->color = $color; $this->type = $type;
    }
    public function getDetails() { return "Мотоцикл: $this->brand ($this->type)"; }
}

class VehicleFactory {
    public static function create($type, $params) {
        switch (strtolower($type)) {
            case 'car': return new Car(...$params);
            case 'bike': return new Bike(...$params);
            case 'motorcycle': return new Motorcycle(...$params);
            default: throw new Exception("Фабрика не може створити даний транспортний засіб: $type");
        }
    }
}

try {
    $car = VehicleFactory::create('car', ['Німеччина', 'BMW', 2022, 'V8', '500 к.с.', 'Чорний']);
    echo $car->getDetails() . "<br>";
    $boat = VehicleFactory::create('boat', ['США', 'Yamaha', 2021]); // Викличе помилку
} catch (Exception $e) {
    echo $e->getMessage() . "<br>";
}
?>