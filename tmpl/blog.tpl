<div class="main col-md">
    <header>
        <h1>Книги</h1>
        <p><span class="font-weight-bold">Найдено</span>: <?= $books_count ?> <?= $books_count_text ?></p>
    </header>
    <?php foreach($books as $book) { ?>
       <div class="media book bg-light border p-2 m-1 book-card">
            <img class="mr-3" src="<?= $book->cover ?>" alt="Обложка книги" />
            <div class="media-body">
                <h5 class="mt-0"><a href="<?= $book->link ?>"><?= $book->title ?></a></h5>
                <p class="text-secondary"><?= $book->author ?></p>
                <p class="text-info small"><?= $book->year ?></p>
                <p class="text-info small text-right"><?= $book->date ?></p>
                <?php if ($is_it_an_admin) { ?>
                    <p><a class="btn btn-outline-success" href="<?=URL::get('editbook', 'admin', ['id' => $book->id])?>" role="button">Редактировать</a></p>
                    <p><a class="btn btn-outline-danger " href="<?=URL::get('delbook', 'admin', ['id' => $book->id])?>" role="button" onclick = "return confirm('Вы уверены?')">Удалить</a></p>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <?= $pagination ?>
</div>
