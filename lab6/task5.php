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

interface EducationRegistryInterface {
    public function getRegistryCard(): string;
    public function getScore100(): int;
    public function exportToJson(): string;
}

class InstitutionRegistryAdapter implements EducationRegistryInterface {
    private Institution $institution;

    public function __construct(Institution $institution) {
        $this->institution = $institution;
    }

    public function getScore100(): int {
        $baseRating = $this->institution->getRating() ?? 0;
        return $baseRating * 10;
    }

    public function getRegistryCard(): string {
        $data = $this->institution->get();
        $score100 = $this->getScore100();
        return "[ЄДЕБО] Заклад: <b>{$data['name']}</b> | Категорія: {$data['type']} | Контингент: {$data['studentsCount']} осіб | Акредитаційний бал: {$score100}/100";
    }

    public function exportToJson(): string {
        $data = $this->institution->get();
        $data['rating_100_scale'] = $this->getScore100();
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

$inst1 = new Institution();
$inst1->set("Гімназія №1", "Гімназія", "вул. Соборна, 15", 850);
$inst1->setRating(9);

$inst2 = new Institution();
$inst2->set("Політехнічний коледж", "Коледж", "пр. Науки, 2", 1200);
$inst2->setRating(8);

$inst3 = new Institution();
$inst3->set("Ліцей 'Лідер'", "Ліцей", "вул. Перемоги, 10", 540);
$inst3->setRating(10);

$institutionsGroup = [$inst1, $inst2, $inst3];

echo "<b>--- Виведення через адаптер для державного реєстру (EducationRegistryInterface) ---</b><br>";
foreach ($institutionsGroup as $inst) {
    $adapter = new InstitutionRegistryAdapter($inst);
    echo $adapter->getRegistryCard() . "<br>";
    echo "<small>JSON API: " . $adapter->exportToJson() . "</small><br><br>";
}

?>