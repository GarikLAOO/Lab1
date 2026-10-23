<?php
class Institution {
    public $name;
    public $type;
    public $address;
    public $studentsCount;
    private $rating;

    public function __construct($name, $type, $address, $studentsCount) {
        $this->name = $name;
        $this->type = $type;
        $this->address = $address;
        $this->studentsCount = $studentsCount;
    }

    public function setRating($rating) {
        if ($rating >= 1 && $rating <= 10) $this->rating = $rating;
    }

    public function show() {
        $ratingStr = $this->rating ?? 'Немає оцінки';
        echo "<b>{$this->name}</b> ({$this->type}) | Адреса: {$this->address} | К-ть учнів: {$this->studentsCount} | Рейтинг: {$ratingStr}<br>";
    }
}

// Проста фабрика для створення закладів
class InstitutionFactory {
    public static function create($name, $type, $address, $studentsCount) {
        return new Institution($name, $type, $address, $studentsCount);
    }
}

echo "<h3>--- Завдання 6: Simple Factory ---</h3>";
$inst1 = InstitutionFactory::create("Гімназія №1", "Гімназія", "вул. Соборна, 15", 850);
$inst1->setRating(9);

$inst2 = InstitutionFactory::create("Політехнічний коледж", "Коледж", "пр. Науки, 2", 1200);
$inst2->setRating(8);

$inst1->show();
$inst2->show();
echo "<hr>";
?>