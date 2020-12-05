<div class="form-group">
    <input class="btn btn-outline-success" type="submit" name="<?php if ($input->name) { ?><?= $input->name ?><?php } else { ?><?= $name ?><?php } ?>" value="<?= $input->value ?>" <?php if (isset($jsv[$input->name])) { ?>data-tconfirm="<?= $jsv[$input->name]->t_confirm ?>"<?php } ?> />
</div>
