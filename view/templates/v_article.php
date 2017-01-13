<?php/*
Шаблон статьи
============================
$articles - статья
content - текст
date_time - дата загрузки статьи
*/?>
<section>
    <p><?php echo nl2br($article['content']); ?></p>
    <small>Дата добавления: <?php echo $article['date_time']; ?></small>
</section>

<?php if(!empty($comments)):?>
<section>
    <?php foreach($comments as $comment): ?>
        <p>
            <b><?php echo $comment['name']; ?>:</b>
            <span class="comment"><?php echo $comment['comment']; ?></span>
        </p>

    <?php endforeach; ?>
</section>
<?php endif; ?>

<section>
    <b class="green"><?php echo vHelper_flashMessage('notice'); ?></b>
    <form method="post" enctype="multipart/form-data" autocomplete="off">
        <label>
            Ваше имя:
            <br>
            <input type="text" name="name" value="<?php echo htmlspecialchars(vHelper_flashMessage('name')); ?>">
        </label>
        <br>
        <br>
        <label>
            Сообщение:
            <br>
            <textarea name="comment"><?php echo htmlspecialchars(vHelper_flashMessage('comment')); ?></textarea>
        </label>
        <br>
        <input type="submit" value="Добавить">
    </form>
</section>
