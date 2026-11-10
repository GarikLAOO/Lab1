<?php

class Institution {
    public $name;
    public $type;
    public $address;
    public $studentsCount;

    private $rating;

    public function set($name, $type, $address, $studentsCount) {
        $this->name = $name;
        $this->type = $type;
        $this->address = $address;
        $this->studentsCount = $studentsCount;
    }

    // Реалізація шаблону Prototype (Варіант 3)
    public function __clone() {
        $this->type = $this->type . " (Філія / Корпус)";
    }

    public function get() {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'address' => $this->address,
            'studentsCount' => $this->studentsCount,
            'rating' => $this->rating
        ];
    }

    private function formatBaseInfo() {
        return "<b>{$this->name}</b> ({$this->type}) | Адреса: {$this->address}";
    }

    public function show() {
        $ratingStr = $this->rating ?? 'Немає оцінки';
        echo $this->formatBaseInfo() . " | К-ть учнів: {$this->studentsCount} | Рейтинг: {$ratingStr}<br>";
    }

    public static function search($array, $searchName) {
        echo "<b>--- Результати пошуку для '{$searchName}' ---</b><br>";
        $found = false;
        foreach ($array as $obj) {
            if ($obj->name === $searchName) {
                $obj->show();
                $found = true;
            }
        }
        if (!$found) echo "Заклад не знайдено.<br>";
        echo "<br>";
    }

    public static function show_objects($array) {
        echo "<b>--- Список усіх навчальних закладів ---</b><br>";
        foreach ($array as $obj) {
            $obj->show();
        }
        echo "<br>";
    }

    public function setRating($rating) {
        if ($rating >= 1 && $rating <= 10) {
            $this->rating = $rating;
        } else {
            echo "Помилка: Рейтинг закладу має бути від 1 до 10.<br>";
        }
    }

    public function getRating() {
        return $this->rating;
    }
}

$gymnasiumPrototype = new Institution();
$gymnasiumPrototype->set("Гімназія №1", "Гімназія", "вул. Соборна, 15", 850);
$gymnasiumPrototype->setRating(9);

$collegePrototype = new Institution();
$collegePrototype->set("Політехнічний коледж", "Коледж", "пр. Науки, 2", 1200);
$collegePrototype->setRating(8);

$lyceumPrototype = new Institution();
$lyceumPrototype->set("Ліцей 'Лідер'", "Ліцей", "вул. Перемоги, 10", 540);
$lyceumPrototype->setRating(10);

$schoolPrototype = new Institution();
$schoolPrototype->set("Школа №42", "ЗОШ", "вул. Квіткова, 7", 400);
$schoolPrototype->setRating(6);

$inst1 = $gymnasiumPrototype;
$inst2 = $collegePrototype;
$inst3 = $lyceumPrototype;
$inst4 = $schoolPrototype;

$inst5 = clone $gymnasiumPrototype;
$inst5->type = "Молодша школа (клон прототипу)";
$inst5->address = "вул. Соборна, 15/А";
$inst5->studentsCount = 300;
$inst5->setRating(8);

$institutionsGroup = [$inst1, $inst2, $inst3, $inst4, $inst5];

Institution::show_objects($institutionsGroup);
Institution::search($institutionsGroup, "Гімназія №1");

?>