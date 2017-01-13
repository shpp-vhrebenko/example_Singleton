<?php/*
Основной шаблон
============================
$title - заголовок
$content - содержание
*/?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

    <header>
        <nav>
            <ul>
                <li>
                    <a <?php vHelper_print_if_true('article', $menuActive, 'red'); ?> href="index.php">Главная</a>
                </li>
                <li>
                    <a <?php vHelper_print_if_true('editor', $menuActive, 'red'); ?> href="index.php?c=editor">Консоль редактора</a>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <h1><?php echo $title_page; ?></h1>
        <?php echo $content; ?>
    </main>

    <footer>
        <small>Все права защищены. Адрес. Телефон.</small>
    </footer>

</body>
</html>