<div class="main col-md">
    <?php if (isset($hornav)) { ?><?= $hornav ?><?php } ?>
    <article>
        <header class="mx-auto">
            <h1><?= $book->title ?></h1>
            <h2><?= $book->author ?></h2>
            <p>Год: <?= $book->year ?></p>
        </header>
        <div>
            <p class="text-justify text-ind"><?= $book->annotation ?></p>
            <?php if ($is_it_an_admin) { ?>
                <p class="mt-2"><a class="btn btn-outline-success " href="<?=URL::get('editbook', 'admin', ['id' => $book->id])?>" role="button">Редактировать</a></p>
                <p class="mt-2"><a class="btn btn-outline-danger " href="<?=URL::get('delbook', 'admin', ['id' => $book->id])?>" role="button" onclick = "return confirm('Вы уверены?')">Удалить</a></p>
            <?php } ?>
        </div>
        <footer class="text-info small">
            <?= $book->date ?>
        </footer>
    </article>
</div>
