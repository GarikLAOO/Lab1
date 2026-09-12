<?php

class TextFileProcessor {
    private $filename;

    public function __construct($filename) {
        $this->filename = $filename;
        
        if (!file_exists($this->filename)) {
            file_put_contents($this->filename, "");
        }
    }

    public function prependLine($newLine) {

        $currentContent = file_get_contents($this->filename);
        
        $updatedContent = $newLine . PHP_EOL . $currentContent;
        
        file_put_contents($this->filename, $updatedContent);
        
        echo "<p>Новий рядок успішно додано на початок файлу!</p>";
    }

    public function showContent() {
        $content = file_get_contents($this->filename);
        echo "<b>Поточний вміст файлу:</b><br>";
        echo (htmlspecialchars($content)); 
    }
}

$testFileName = "data.txt";

file_put_contents($testFileName, "Старий рядок 1" . PHP_EOL . "Старий рядок 2");

$fileEditor = new TextFileProcessor($testFileName);

$fileEditor->prependLine("ЦЕЙ РЯДОК ДОДАНО НА ПОЧАТОК");

$fileEditor->showContent();

?>