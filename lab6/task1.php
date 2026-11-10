<?php

abstract class AbstractCar {
    protected string $boardNumber = "Б/Н";
    abstract public function getDetails();
    abstract public function __clone();
}

abstract class AbstractTruck {
    protected string $boardNumber = "Б/Н";
    abstract public function getDetails();
    abstract public function __clone();
}

abstract class AbstractBus {
    protected string $boardNumber = "Б/Н";
    abstract public function getDetails();
    abstract public function __clone();
}

class UaCar extends AbstractCar {
    public function __clone() {
        $this->boardNumber = "UA-CAR-" . rand(100, 999);
    }
    public function getDetails() {
        return "ЗАЗ Сенс (Легкова машина) [Бортовий №: {$this->boardNumber}]";
    }
}

class UaTruck extends AbstractTruck {
    public function __clone() {
        $this->boardNumber = "UA-TRUCK-" . rand(100, 999);
    }
    public function getDetails() {
        return "КрАЗ-255 (Вантажівка) [Бортовий №: {$this->boardNumber}]";
    }
}

class UaBus extends AbstractBus {
    public function __clone() {
        $this->boardNumber = "UA-BUS-" . rand(100, 999);
    }
    public function getDetails() {
        return "Богдан А092 (Автобус) [Бортовий №: {$this->boardNumber}]";
    }
}

class ForeignCar extends AbstractCar {
    public function __clone() {
        $this->boardNumber = "INT-CAR-" . rand(100, 999);
    }
    public function getDetails() {
        return "Toyota Camry (Легкова машина) [Бортовий №: {$this->boardNumber}]";
    }
}

class ForeignTruck extends AbstractTruck {
    public function __clone() {
        $this->boardNumber = "INT-TRUCK-" . rand(100, 999);
    }
    public function getDetails() {
        return "Volvo FH16 (Вантажівка) [Бортовий №: {$this->boardNumber}]";
    }
}

class ForeignBus extends AbstractBus {
    public function __clone() {
        $this->boardNumber = "INT-BUS-" . rand(100, 999);
    }
    public function getDetails() {
        return "Mercedes-Benz Tourismo (Автобус) [Бортовий №: {$this->boardNumber}]";
    }
}

interface VehicleFleetFactory {
    public function createCar(): AbstractCar;
    public function createTruck(): AbstractTruck;
    public function createBus(): AbstractBus;
}

class UaFactory implements VehicleFleetFactory {
    private AbstractCar $carPrototype;
    private AbstractTruck $truckPrototype;
    private AbstractBus $busPrototype;

    public function __construct() {
        $this->carPrototype = new UaCar();
        $this->truckPrototype = new UaTruck();
        $this->busPrototype = new UaBus();
    }

    public function createCar(): AbstractCar { return clone $this->carPrototype; }
    public function createTruck(): AbstractTruck { return clone $this->truckPrototype; }
    public function createBus(): AbstractBus { return clone $this->busPrototype; }
}

class ForeignFactory implements VehicleFleetFactory {
    private AbstractCar $carPrototype;
    private AbstractTruck $truckPrototype;
    private AbstractBus $busPrototype;

    public function __construct() {
        $this->carPrototype = new ForeignCar();
        $this->truckPrototype = new ForeignTruck();
        $this->busPrototype = new ForeignBus();
    }

    public function createCar(): AbstractCar { return clone $this->carPrototype; }
    public function createTruck(): AbstractTruck { return clone $this->truckPrototype; }
    public function createBus(): AbstractBus { return clone $this->busPrototype; }
}

function buildFleet($configString) {
    // Парсинг рядка конфігурації
    preg_match_all('/(\w+)=(\w+)/', $configString, $matches);
    $config = array_combine($matches[1], $matches[2]);

    $factoryType = $config['factory'] ?? 'ua';

    if ($factoryType === 'ua') {
        $factory = new UaFactory();
        echo "<b>Створення вітчизняного автопарку (клонуванням прототипів):</b><br>";
    } else {
        $factory = new ForeignFactory();
        echo "<b>Створення зарубіжного автопарку (клонуванням прототипів):</b><br>";
    }

    $fleet = [];

    // Наповнення парку шляхом клонування прототипів
    for ($i = 0; $i < (int)($config['carNum'] ?? 0); $i++) {
        $fleet[] = $factory->createCar();
    }
    for ($i = 0; $i < (int)($config['truckNum'] ?? 0); $i++) {
        $fleet[] = $factory->createTruck();
    }
    for ($i = 0; $i < (int)($config['busNum'] ?? 0); $i++) {
        $fleet[] = $factory->createBus();
    }

    foreach ($fleet as $index => $vehicle) {
        echo ($index + 1) . ". " . $vehicle->getDetails() . "<br>";
    }
    echo "<i>Загальна кількість техніки: " . count($fleet) . " од.</i><hr>";
}

$config1 = "factory=ua carNum=10 truckNum=2 busNum=4";
buildFleet($config1);

$config2 = "factory=foreign carNum=3 truckNum=1 busNum=2";
buildFleet($config2);

?>