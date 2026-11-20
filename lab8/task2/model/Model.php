<?php
include_once(__DIR__ . "/Database.php");

class Model
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getBooks(string $title = '', string $author = ''): array
    {
        $sql = "
            SELECT b.title, b.description, b.publish_year, b.pages, b.cover_image,
                   a.last_name, a.first_name, a.country
            FROM books b
            INNER JOIN authors a ON b.author_id = a.id
            WHERE 1=1
        ";
        $params = [];

        if ($title !== '') {
            $sql .= " AND b.title LIKE :title";
            $params[':title'] = "%{$title}%";
        }
        if ($author !== '') {
            $sql .= " AND (a.last_name LIKE :author OR a.first_name LIKE :author)";
            $params[':author'] = "%{$author}%";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
?>