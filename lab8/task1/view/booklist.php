<?php /** @var Book[] $books */ ?>
<html>
<head><meta charset="UTF-8"></head>
<body>
    <table border="1" cellpadding="5">
        <tbody>
            <tr><td>Title</td><td>Author</td><td>Description</td></tr>
            <?php
                foreach ($books as $title => $book) {
                    echo '<tr>'
                        . '<td><a href="index.php?book=' . urlencode($book->title) . '">' . $book->title . '</a></td>'
                        . '<td>' . $book->author . '</td>'
                        . '<td>' . $book->description . '</td>'
                        . '</tr>';
                }
            ?>
        </tbody>
    </table>
</body>
</html>