<?php
// Трейт із загальним функціоналом
trait InstitutionInfoTrait {
    public function formatBaseInfo() {
        echo "<b>{$this->name}</b> ({$this->type}) | Адреса: {$this->address}<br>";
    }
}

// Інтерфейси продуктів (Школа та Коледж)
interface AbstractSchool { public function getSchoolInfo(); }
interface AbstractCollege { public function getCollegeInfo(); }

// Сімейство 1: Державні заклади
class PublicSchool implements AbstractSchool {
    use InstitutionInfoTrait;
    private $name, $type = "Державна школа", $address;
    
    public function __construct($name, $address) {
        $this->name = $name; $this->address = $address;
    }
    public function getSchoolInfo() { $this->formatBaseInfo(); }
}

class PublicCollege implements AbstractCollege {
    use InstitutionInfoTrait;
    private $name, $type = "Державний коледж", $address;
    
    public function __construct($name, $address) {
        $this->name = $name; $this->address = $address;
    }
    public function getCollegeInfo() { $this->formatBaseInfo(); }
}

// Сімейство 2: Приватні заклади
class PrivateSchool implements AbstractSchool {
    use InstitutionInfoTrait;
    private $name, $type = "Приватна школа", $address;
    
    public function __construct($name, $address) {
        $this->name = $name; $this->address = $address;
    }
    public function getSchoolInfo() { $this->formatBaseInfo(); }
}

class PrivateCollege implements AbstractCollege {
    use InstitutionInfoTrait;
    private $name, $type = "Приватний коледж", $address;
    
    public function __construct($name, $address) {
        $this->name = $name; $this->address = $address;
    }
    public function getCollegeInfo() { $this->formatBaseInfo(); }
}

// Абстрактна фабрика
interface AbstractInstitutionFactory {
    public function createSchool($name, $address): AbstractSchool;
    public function createCollege($name, $address): AbstractCollege;
}

// Конкретні фабрики сімейств
class PublicInstitutionFactory implements AbstractInstitutionFactory {
    public function createSchool($name, $address): AbstractSchool {
        return new PublicSchool($name, $address);
    }
    public function createCollege($name, $address): AbstractCollege {
        return new PublicCollege($name, $address);
    }
}

class PrivateInstitutionFactory implements AbstractInstitutionFactory {
    public function createSchool($name, $address): AbstractSchool {
        return new PrivateSchool($name, $address);
    }
    public function createCollege($name, $address): AbstractCollege {
        return new PrivateCollege($name, $address);
    }
}

echo "<h3>--- Завдання 13: Abstract Factory + Traits ---</h3>";
// Створюємо сімейство державних закладів
$publicFactory = new PublicInstitutionFactory();
$pubSchool = $publicFactory->createSchool("ЗОШ №15", "вул. Лесі Українки, 5");
$pubCollege = $publicFactory->createCollege("Аграрний коледж", "вул. Садова, 1");

echo "<i>Державний сектор:</i><br>";
$pubSchool->getSchoolInfo();
$pubCollege->getCollegeInfo();

// Створюємо сімейство приватних закладів
$privateFactory = new PrivateInstitutionFactory();
$privSchool = $privateFactory->createSchool("Ліцей 'Інтелект'", "вул. Франка, 10");
$privCollege = $privateFactory->createCollege("IT-коледж 'Future'", "пр. Перемоги, 55");

echo "<br><i>Приватний сектор:</i><br>";
$privSchool->getSchoolInfo();
$privCollege->getCollegeInfo();
?>