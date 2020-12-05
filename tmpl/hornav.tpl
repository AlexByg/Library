<nav>
    <ul class="breadcrumb" style="background-color: #fff;">
        <?php foreach ($data as $d) { ?>
            <?php if ($d->link) { ?>
                <li class="breadcrumb-item"><a href="<?= $d->link ?>"><?= $d->title ?></a></li>
            <?php } else { ?>
                <li class="breadcrumb-item active"><?= $d->title ?></li>
            <?php } ?>
        <?php } ?>
    </ul>
</nav>
