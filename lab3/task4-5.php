<?php
interface ILoger {
    public function log($message);
}

// Завдання 4
class FileLoger implements ILoger {
    private $file;

    public function __construct($filename, $mode = 'a') {
        $this->file = fopen($filename, $mode) or die('Could not open the log file');
    }

    public function log($message) {
        $formattedMessage = date("F j, Y, g:i a") . ': ' . $message . "\n";
        fwrite($this->file, $formattedMessage);
        echo "Записано у файл: $formattedMessage <br>";
    }

    public function __destruct() {
        if ($this->file) {
            fclose($this->file);
        }
    }
}

// Завдання 5
class DbLoger implements ILoger {
    public function log($message) {
        $date = date("Y-m-d H:i:s");

        $sql = "INSERT INTO logs (date, message) VALUES ('$date', '$message')";
        echo "Виконано SQL запит: $sql <br>";
    }
}

$fileLogger = new FileLoger('./log.txt', 'w');
$fileLogger->log('Повідомлення у файл 1');
$fileLogger->log('Повідомлення у файл 2');

$dbLogger = new DbLoger();
$dbLogger->log('Повідомлення у базу даних');
echo "<hr>";
?>