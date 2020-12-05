<?php
    $count_pages = ceil($count_elements / $count_on_page);
    if ($count_pages > 1) {
        $left = $active - 1;
        if ($left < floor($count_show_pages / 2)) $start = 1;
        else $start = $active - floor($count_show_pages / 2);
        $end = $start + $count_show_pages - 1;
        if ($end > $count_pages) {
            $start -= ($end - $count_pages);
            $end = $count_pages;
            if ($start < 1) $start = 1;
        }
?>
    <div id="pagination">
        <nav>
            <ul class="pagination">
                <?php if ($active != 1) { ?>
                    <li class="page-item"><a class="page-link" href="<?= UseSEF::replaceSEF($url) ?>" title="Первая">&lt;&lt;</a></li>
                    <li class="page-item"><a class="page-link" href="<?php if ($active == 2) { ?><?= UseSEF::replaceSEF($url) ?><?php } else { ?><?= UseSEF::replaceSEF($url_page.($active - 1)) ?><?php } ?>" title="Предыдущая">&lt;</a></li>
                <?php } else { ?>
                    <li class="page-item disabled"><a class="page-link" href="#">&lt;&lt;</a></li>
                    <li class="page-item disabled"><a class="page-link" href="#">&lt;</a></li>
                <?php } ?>
                <?php for ($i = $start; $i <= $end; $i++) { ?>
                    <li class="page-item <?php if ($i == $active) { ?>active<?php } ?>"><a class="page-link" href="<?php if ($i == 1) { ?><?= UseSEF::replaceSEF($url) ?><?php } else { ?><?= UseSEF::replaceSEF($url_page.$i) ?><?php } ?>"><?= $i ?></a></li>
                <?php } ?>
                <?php if ($active != $count_pages) { ?>
                    <li class="page-item"><a class="page-link" href="<?= UseSEF::replaceSEF($url_page.($active + 1)) ?>" title="Следующая">&gt;</a></li>
                    <li class="page-item"><a class="page-link" href="<?= UseSEF::replaceSEF($url_page.$count_pages) ?>" title="Последняя">&gt;&gt;</a></li>
                <?php } else { ?>
                    <li class="page-item disabled"><a class="page-link" href="#">&gt;</a></li>
                    <li class="page-item disabled"><a class="page-link" href="#">&gt;&gt;</a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>
<?php } ?>
