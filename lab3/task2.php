<?php
abstract class Figure {
    protected $x;
    protected $y;

    public function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    public function getCenterCoordinates() {
        echo "Координати центру: ($this->x, $this->y)<br>";
    }

    abstract public function area();
}

class Circle extends Figure {
    private $radius;

    public function __construct($x, $y, $radius) {
        parent::__construct($x, $y);
        $this->radius = $radius;
    }

    public function area() {
        $area = pi() * pow($this->radius, 2);
        echo "Площа кола: " . round($area, 2) . "<br>";
    }
}

class Rectangle extends Figure {
    private $width;
    private $height;

    public function __construct($x, $y, $width, $height) {
        parent::__construct($x, $y);
        $this->width = $width;
        $this->height = $height;
    }

    public function area() {
        $area = $this->width * $this->height;
        echo "Площа прямокутника: $area<br>";
    }
}


$circle = new Circle(5, 5, 10);
$circle->getCenterCoordinates();
$circle->area();

$rect = new Rectangle(0, 0, 4, 5);
$rect->getCenterCoordinates();
$rect->area();
echo "<hr>";
?>