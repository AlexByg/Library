<?php if (isset($jsv[$input->name])) { ?>data-type="<?= $jsv[$input->name]->type ?>" data-minlen="<?= $jsv[$input->name]->min_len ?>" data-maxlen="<?= $jsv[$input->name]->max_len ?>" data-tminlen="<?= $jsv[$input->name]->t_min_len ?>" data-tmaxlen="<?= $jsv[$input->name]->t_max_len ?>" data-tempty="<?= $jsv[$input->name]->t_empty ?>" data-ttype="<?= $jsv[$input->name]->t_type ?>" data-minval="<?=$jsv[$input->name]->min_val?>" data-maxval="<?=$jsv[$input->name]->max_val?>"<?php } ?>
