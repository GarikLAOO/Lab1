CREATE DATABASE IF NOT EXISTS mvc_books CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mvc_books;

CREATE TABLE authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL
);

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    publish_year INT NOT NULL,
    pages INT NOT NULL,
    cover_image VARCHAR(255),
    author_id INT NOT NULL,
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