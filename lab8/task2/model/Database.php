<?php
class Database
{
    public static function connect(): PDO
    {
        $dbPath = __DIR__ . '/../mvc_books.sqlite';
        $isNew = !file_exists($dbPath);

        $pdo = new PDO("sqlite:" . $dbPath, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        if ($isNew) {
            $pdo->exec("
                CREATE TABLE authors (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    last_name TEXT NOT NULL,
                    first_name TEXT NOT NULL,
                    country TEXT NOT NULL
                );

                CREATE TABLE books (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title TEXT NOT NULL,
                    description TEXT,
                    publish_year INTEGER NOT NULL,
                    pages INTEGER NOT NULL,
                    cover_image TEXT,
                    author_id INTEGER NOT NULL,
                    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
                );

                INSERT INTO authors (last_name, first_name, country) VALUES
                ('Шевченко', 'Тарас', 'Україна'),
                ('Кіплінг', 'Редьярд', 'Велика Британія'),
                ('Орвелл', 'Джордж', 'Велика Британія'),
                ('Костенко', 'Ліна', 'Україна');

                INSERT INTO books (title, description, publish_year, pages, cover_image, author_id) VALUES
                ('Кобзар', 'Збірка поетичних творів українського класика.', 1840, 720, 'https://via.placeholder.com/70x100?text=Kobzar', 1),
                ('Книга джунглів', 'Оповідання про пригоди Мауглі.', 1894, 240, 'https://via.placeholder.com/70x100?text=Jungle', 2),
                ('1984', 'Класичний роман-антиутопія.', 1949, 328, 'https://via.placeholder.com/70x100?text=1984', 3),
                ('Маруся Чурай', 'Історичний роман у віршах.', 1979, 224, 'https://via.placeholder.com/70x100?text=Marusya', 4);
            ");
        }

        return $pdo;
    }
}
?>