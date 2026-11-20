<?php

// Клас предметної області з лабораторної роботи №1
class Institution
{
    public $name;
    public $type;
    public $address;
    public $studentsCount;
    private $rating;

    public function set($name, $type, $address, $studentsCount)
    {
        $this->name = $name;
        $this->type = $type;
        $this->address = $address;
        $this->studentsCount = $studentsCount;
    }

    public function setRating($rating)
    {
        if ($rating >= 1 && $rating <= 10) {
            $this->rating = $rating;
        }
    }

    public function getRating()
    {
        return $this->rating ?? 'Немає оцінки';
    }
}

// Абстрактний обробник для навчальних закладів
abstract class DepartmentHandler
{
    protected ?DepartmentHandler $next = null;

    public function setNext(DepartmentHandler $handler): DepartmentHandler
    {
        $this->next = $handler;
        return $handler;
    }

    public function handle(Institution $institution): void
    {
        if ($this->next !== null) {
            $this->next->handle($institution);
        } else {
            echo "Заклад <b>«{$institution->name}»</b> (тип: {$institution->type}) — "
               . "<i>не знайдено відповідного відділу для обробки.</i><br>";
        }
    }
}

// Відділ загальної середньої освіти (ЗОШ та Молодша школа)
class SchoolDepartment extends DepartmentHandler
{
    public function handle(Institution $institution): void
    {
        if (in_array($institution->type, ['ЗОШ', 'Молодша школа'])) {
            echo "[Відділ шкільної освіти] Прийнято на облік: <b>{$institution->name}</b> "
               . "({$institution->type}, учнів: {$institution->studentsCount}, рейтинг: {$institution->getRating()}).<br>";
        } else {
            parent::handle($institution);
        }
    }
}

// Відділ профільних закладів (Гімназії та Ліцеї)
class SpecializedEducationDepartment extends DepartmentHandler
{
    public function handle(Institution $institution): void
    {
        if (in_array($institution->type, ['Гімназія', 'Ліцей'])) {
            echo "[Відділ профільної освіти] Прийнято на облік: <b>{$institution->name}</b> "
               . "({$institution->type}, адреса: {$institution->address}, рейтинг: {$institution->getRating()}).<br>";
        } else {
            parent::handle($institution);
        }
    }
}

// Відділ фахової передвищої освіти (Коледжі)
class CollegeDepartment extends DepartmentHandler
{
    public function handle(Institution $institution): void
    {
        if ($institution->type === 'Коледж') {
            echo "[Відділ передвищої освіти] Прийнято на облік: <b>{$institution->name}</b> "
               . "({$institution->type}, студентів: {$institution->studentsCount}, рейтинг: {$institution->getRating()}).<br>";
        } else {
            parent::handle($institution);
        }
    }
}

// Створюємо об'єкти з лабораторної №1
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
$inst5->set("Університет майбутнього", "Університет", "вул. Студентська, 1", 3500);
$inst5->setRating(9);

$institutions = [$inst1, $inst2, $inst3, $inst4, $inst5];

// Будуємо ланцюжок відділів
$schoolDept = new SchoolDepartment();
$specializedDept = new SpecializedEducationDepartment();
$collegeDept = new CollegeDepartment();

$schoolDept->setNext($specializedDept)->setNext($collegeDept);

echo "<h3>Розподіл навчальних закладів по відділах:</h3>";
foreach ($institutions as $inst) {
    $schoolDept->handle($inst);
}
?>