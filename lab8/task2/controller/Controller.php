<?php
include_once("model/Model.php");

class Controller
{
    private Model $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function invoke(): void
    {
        $title = trim($_GET['title'] ?? '');
        $author = trim($_GET['author'] ?? '');

        $books = $this->model->getBooks($title, $author);
        include 'view/booklist.php';
    }
}
?>