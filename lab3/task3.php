<?php
    abstract class Ministry {
        protected $name;
        protected $address;

        public function __construct($name, $address) {
            $this->name = $name;
            $this->address = $address;
        }

        public function getName() {
            return $this->name;
        }

        abstract public function showInfo();
    }

    class Institution extends Ministry {
        public $type;
        public $studentsCount;
        private $rating;

        public function __construct($name, $type, $address, $studentsCount) {
            parent::__construct($name, $address);
            $this->type = $type;
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
                if ($obj->getName() === $searchName) {
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

        public function showInfo() {
            echo "Це інститут/коледж: {$this->name}, розташований за адресою: {$this->address}.<br>";
        }
    }

    class School extends Ministry {
        public function __construct($name, $address) {
            parent::__construct($name, $address);
        }

        public function showInfo() {
            echo "Це школа: {$this->name}, розташована за адресою: {$this->address}.<br>";
        }
    }

    echo "<b>--- Демонстрація абстрактного методу ---</b><br>";
    $inst = new Institution("Політехнічний інститут", "Інститут", "пр. Науки, 2", 1200);
    $inst->showInfo();

    $school = new School("Ліцей 'Лідер'", "вул. Перемоги, 10");
    $school->showInfo();
    echo "<br>";

    $inst1 = new Institution("Гімназія №1", "Гімназія", "вул. Соборна, 15", 850);
    $inst1->setRating(9); 

    $inst2 = new Institution("Школа №42", "ЗОШ", "вул. Квіткова, 7", 400);
    $inst2->setRating(6);

    $institutionsGroup = [$inst1, $inst2, $inst];

    Institution::show_objects($institutionsGroup);
    Institution::search($institutionsGroup, "Гімназія №1");

?>