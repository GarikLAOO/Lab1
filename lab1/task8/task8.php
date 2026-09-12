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

class InstitutionCSV {
    private $filename;

    public function __construct($filename) {
        $this->filename = $filename;
    }

    public function saveInstitutions(array $institutions) {
        $file = fopen($this->filename, "w"); 
        
        foreach ($institutions as $inst) {
            $data = $inst->get(); 
            fputcsv($file, [
                $data['name'], 
                $data['type'], 
                $data['address'], 
                $data['studentsCount'], 
                $data['rating']
            ], ",", "\"", "\\");
        }
        fclose($file);
        echo "<p><i>Дані успішно збережено у файл {$this->filename}.</i></p>";
    }

    public function loadInstitutions() {
        if (!file_exists($this->filename)) {
            throw new Exception("Файл не знайдено.");
        }

        $institutions = [];
        $file = fopen($this->filename, "r");

        while (($row = fgetcsv($file, 0, ",", "\"", "\\")) !== FALSE) {
            $inst = new Institution();
            $inst->set($row[0], $row[1], $row[2], (int)$row[3]);
            
            if (isset($row[4]) && $row[4] !== '') {
                $inst->setRating((int)$row[4]);
            }
            
            $institutions[] = $inst;
        }
        fclose($file);
        return $institutions;
    }
}

$inst1 = new Institution(); $inst1->set("Гімназія №1", "Гімназія", "вул. Соборна, 15", 850); $inst1->setRating(9); 
$inst2 = new Institution(); $inst2->set("Політехнічний коледж", "Коледж", "пр. Науки, 2", 1200); $inst2->setRating(8);
$inst3 = new Institution(); $inst3->set("Ліцей 'Лідер'", "Ліцей", "вул. Перемоги, 10", 540); $inst3->setRating(10);
$inst4 = new Institution(); $inst4->set("Школа №42", "ЗОШ", "вул. Квіткова, 7", 400); $inst4->setRating(6);
$inst5 = new Institution(); $inst5->set("Гімназія №1", "Молодша школа", "вул. Соборна, 15/А", 300); $inst5->setRating(8);

$originalGroup = [$inst1, $inst2, $inst3, $inst4, $inst5];

$csvManager = new InstitutionCSV("institutions_data.csv");
$csvManager->saveInstitutions($originalGroup);

echo "<b>--- Відновлення даних з CSV файлу ---</b><br>";
try {
    $loadedGroup = $csvManager->loadInstitutions();
    
    Institution::show_objects($loadedGroup);
    
} catch (Exception $e) {
    echo "Помилка: " . $e->getMessage();
}

?>