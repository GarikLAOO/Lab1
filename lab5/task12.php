<?php

// --- 1. Абстрактні класи продуктів (Транспортні засоби) ---
abstract class AbstractCar {
    abstract public function getDetails();
}

abstract class AbstractTruck {
    abstract public function getDetails();
}

abstract class AbstractBus {
    abstract public function getDetails();
}

// --- 2. Конкретні продукти: Вітчизняні ---
class UaCar extends AbstractCar {
    public function getDetails() { return "ЗАЗ Сенс (Легкова машина)"; }
}
class UaTruck extends AbstractTruck {
    public function getDetails() { return "КрАЗ-255 (Вантажівка)"; }
}
class UaBus extends AbstractBus {
    public function getDetails() { return "Богдан А092 (Автобус)"; }
}

// --- 3. Конкретні продукти: Зарубіжні ---
class ForeignCar extends AbstractCar {
    public function getDetails() { return "Toyota Camry (Легкова машина)"; }
}
class ForeignTruck extends AbstractTruck {
    public function getDetails() { return "Volvo FH16 (Вантажівка)"; }
}
class ForeignBus extends AbstractBus {
    public function getDetails() { return "Mercedes-Benz Tourismo (Автобус)"; }
}

// --- 4. Інтерфейс Абстрактної Фабрики ---
interface VehicleFleetFactory {
    public function createCar(): AbstractCar;
    public function createTruck(): AbstractTruck;
    public function createBus(): AbstractBus;
}

// --- 5. Конкретні фабрики ---
class UaFactory implements VehicleFleetFactory {
    public function createCar(): AbstractCar { return new UaCar(); }
    public function createTruck(): AbstractTruck { return new UaTruck(); }
    public function createBus(): AbstractBus { return new UaBus(); }
}

class ForeignFactory implements VehicleFleetFactory {
    public function createCar(): AbstractCar { return new ForeignCar(); }
    public function createTruck(): AbstractTruck { return new ForeignTruck(); }
    public function createBus(): AbstractBus { return new ForeignBus(); }
}

function buildFleet($configString) {
    // Парсинг рядка конфігурації (наприклад: "factory=ua carNum=10 truckNum=2 busNum=4")
    preg_match_all('/(\w+)=(\w+)/', $configString, $matches);
    $config = array_combine($matches[1], $matches[2]);

    $factoryType = $config['factory'] ?? 'ua';

    // Вибір фабрики на основі конфігурації
    if ($factoryType === 'ua') {
        $factory = new UaFactory();
        echo "<b>Створення вітчизняного автопарку:</b><br>";
    } else {
        $factory = new ForeignFactory();
        echo "<b>Створення зарубіжного автопарку:</b><br>";
    }

    $fleet = [];
    
    // Наповнення парку згідно з кількістю в конфігурації
    for ($i = 0; $i < (int)($config['carNum'] ?? 0); $i++) {
        $fleet[] = $factory->createCar();
    }
    for ($i = 0; $i < (int)($config['truckNum'] ?? 0); $i++) {
        $fleet[] = $factory->createTruck();
    }
    for ($i = 0; $i < (int)($config['busNum'] ?? 0); $i++) {
        $fleet[] = $factory->createBus();
    }

    // Виведення результату
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