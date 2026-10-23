<?php
interface ILoger {
    public function log($message);
}

trait DateTrait {
    public function getCurrentDate() {
        return date("F j, Y, g:i a");
    }
}

trait FileWriteTrait {
    public function writeToFile($filename, $content) {
        $file = fopen($filename, 'a') or die('Could not open file');
        fwrite($file, $content . "\n");
        fclose($file);
    }
}

class FileLoger implements ILoger {
    use DateTrait, FileWriteTrait;
    
    private $filename;

    public function __construct($filename) {
        $this->filename = $filename;
    }

    public function log($message) {
        $formattedMessage = $this->getCurrentDate() . ": " . $message;
        $this->writeToFile($this->filename, $formattedMessage);
        echo "Лог записано: $formattedMessage<br>";
    }
}

$logger = new FileLoger('./log.txt');
$logger->log('log message'); 
$logger->log('another log message');
echo "<hr>";
?>