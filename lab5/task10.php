<?php
// Спільний інтерфейс для всіх продуктів
interface IInstitution {
    public function showDetails();
}

// Конкретні продукти
class SchoolProduct implements IInstitution {
    private $name, $address;
    public function __construct($name, $address) {
        $this->name = $name; $this->address = $address;
    }
    public function showDetails() { echo "Школа: {$this->name}, Адреса: {$this->address}<br>"; }
}

class CollegeProduct implements IInstitution {
    private $name, $address;
    public function __construct($name, $address) {
        $this->name = $name; $this->address = $address;
    }
    public function showDetails() { echo "Коледж: {$this->name}, Адреса: {$this->address}<br>"; }
}

// Інтерфейс Фабричного методу
interface InstitutionCreator {
    public function createInstitution($name, $address): IInstitution;
}

// Конкретні фабрики, що самі вирішують, який клас інстанціювати
class SchoolCreator implements InstitutionCreator {
    public function createInstitution($name, $address): IInstitution {
        return new SchoolProduct($name, $address);
    }
}

class CollegeCreator implements InstitutionCreator {
    public function createInstitution($name, $address): IInstitution {
        return new CollegeProduct($name, $address);
    }
}

echo "<h3>--- Завдання 10: Factory Method ---</h3>";
$schoolFactory = new SchoolCreator();
$school = $schoolFactory->createInstitution("Школа №42", "вул. Квіткова, 7");
$school->showDetails();

$collegeFactory = new CollegeCreator();
$college = $collegeFactory->createInstitution("Будівельний коледж", "вул. Миру, 10");
$college->showDetails();
echo "<hr>";
?>