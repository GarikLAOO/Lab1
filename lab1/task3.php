<?php

    class Institution{

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

    $inst1 = new Institution();
    $inst1->set("Гімназія №1", "Гімназія", "вул. Соборна, 15", 850);
    $inst1->setRating(9); 

    $inst2 = new Institution();
    $inst2->set("Політехнічний коледж", "Коледж", "пр. Науки, 2", 1200);
    $inst2->setRating(8);

    $inst3 = new Institution();
    $inst3->set("Ліцей 'Лідер'", "Ліцей", "вул. Перемоги, 10", 540);
    $inst3->setRating(10);

    $inst4 = new Institution();
    $inst4->set("Школа №42", "ЗОШ", "вул. Квіткова, 7", 400);
    $inst4->setRating(6);

    $inst5 = new Institution();
    $inst5->set("Гімназія №1", "Молодша школа", "вул. Соборна, 15/А", 300); 
    $inst5->setRating(8);

    $institutionsGroup = [$inst1, $inst2, $inst3, $inst4, $inst5];

    Institution::show_objects($institutionsGroup);

    Institution::search($institutionsGroup, "Гімназія №1");

?>