<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Каталог книг (MVC + MySQL)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { margin-bottom: 15px; }
        input { padding: 5px; margin-right: 8px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Каталог книг</h2>

    <form method="GET" action="index.php">
        <input type="text" name="title" placeholder="Назва книги" value="<?= htmlspecialchars($title) ?>">
        <input type="text" name="author" placeholder="Автор (ім'я або прізвище)" value="<?= htmlspecialchars($author) ?>">
        <button type="submit">Пошук</button>
        <a href="index.php">Скинути</a>
    </form>

    <?php if (empty($books)): ?>
        <p>Книг за вашим запитом не знайдено.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Обкладинка</th>
                <th>Назва</th>
                <th>Опис</th>
                <th>Рік</th>
                <th>Сторінок</th>
                <th>Автор</th>
                <th>Країна</th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="Обкладинка" width="60"></td>
                    <td><b><?= htmlspecialchars($book['title']) ?></b></td>
                    <td><?= htmlspecialchars($book['description']) ?></td>
                    <td><?= (int)$book['publish_year'] ?></td>
                    <td><?= (int)$book['pages'] ?></td>
                    <td><?= htmlspecialchars($book['first_name'] . ' ' . $book['last_name']) ?></td>
                    <td><?= htmlspecialchars($book['country']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>