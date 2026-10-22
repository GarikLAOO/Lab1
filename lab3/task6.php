<?php

namespace Task6;

interface Figure {
    public function draw();
    public function erase();
    public function move($x, $y);
    public function getColor();
    public function setColor($color);
}

class Circle implements Figure {
    private $color = "Червоний";

    public function draw() {
        echo "Малюємо коло...<br>";
    }

    public function erase() {
        echo "Стираємо коло.<br>";
    }

    public function move($x, $y) {
        echo "Коло переміщено в позицію X: $x, Y: $y.<br>";
    }

    public function getColor() {
        return $this->color;
    }

    public function setColor($color) {
        $this->color = $color;
    }
}

// Клас Квадрат
class Square implements Figure {
    private $color = "Синій";

    public function draw() {
        echo "Малюємо квадрат...<br>";
    }

    public function erase() {
        echo "Стираємо квадрат.<br>";
    }

    public function move($x, $y) {
        echo "Квадрат переміщено в позицію X: $x, Y: $y.<br>";
    }

    public function getColor() {
        return $this->color;
    }

    public function setColor($color) {
        $this->color = $color;
    }
}

class Triangle implements Figure {
    private $color = "Зелений";

    public function draw() {
        echo "Малюємо трикутник...<br>";
    }

    public function erase() {
        echo "Стираємо трикутник.<br>";
    }

    public function move($x, $y) {
        echo "Трикутник переміщено в позицію X: $x, Y: $y.<br>";
    }

    public function getColor() {
        return $this->color;
    }

    public function setColor($color) {
        $this->color = $color;
    }
}

$figures = [
    new Circle(),
    new Square(),
    new Triangle()
];

foreach ($figures as $index => $fig) {
    echo "<b>Фігура #" . ($index + 1) . " початковий колір: " . $fig->getColor() . "</b><br>";
    $fig->draw();
    $fig->setColor("Жовтий");
    echo "Змінений колір: " . $fig->getColor() . "<br>";
    $fig->move(15, 30);
    $fig->erase();
    echo "<br>";
}
?>