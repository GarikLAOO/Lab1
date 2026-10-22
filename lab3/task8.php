<?php
interface IReadable {
    public function read();
}

interface ISellable {
    public function getPrice();
}

class EBook implements IReadable, ISellable {
    private $title;
    private $price;

    public function __construct($title, $price) {
        $this->title = $title;
        $this->price = $price;
    }

    public function read() {
        echo "Користувач читає електронну книгу: '{$this->title}'.<br>";
    }

    public function getPrice() {
        echo "Ціна електронної книги '{$this->title}' становить {$this->price} грн.<br>";
    }
}

$myBook = new EBook("Об'єктно-орієнтоване програмування в PHP", 350);
$myBook->read();
$myBook->getPrice();
?>