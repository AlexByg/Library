<div class="main col-md">
    <?php if (isset($hornav)) { ?><?=$hornav?><?php } ?>
    <div id="editbook">
        <h1>Редактирование</h1>
        <div>
            <img src="<?=$cover?>" alt="Обложка" />
        </div>
        <div class="cover_info">
            <p>Допустимые форматы - <b>GIF</b>, <b>JPG</b>, <b>PNG</b></p>
            <p>Размер изображения должен быть <b>не более <?=$max_size?> КБ</b>!</p>
        </div>
        <div class="d-flex flex-column">
            <?=$form_cover?>
            <?=$form_text?>
        </div>
    </div>
</div>
