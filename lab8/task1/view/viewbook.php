<?php /** @var Book $book */ ?>
<html>
<head><meta charset="UTF-8"></head>
<body>
    <?php
        echo 'Title: ' . $book->title . '<br/>';
        echo 'Author: ' . $book->author . '<br/>';
        echo 'Description: ' . $book->description . '<br/>';
    ?>
    <br><a href="index.php">Назад до списку</a>
</body>
</html>