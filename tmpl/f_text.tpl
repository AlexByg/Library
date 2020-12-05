<div class="form-group">
    <label for="<?= $input->name ?>"><?= $input->label ?></label>
    <input class="form-control" id="<?= $input->name ?>" type="text" name="<?= $input->name ?>" <?php if ($input->value) { ?>value="<?= $input->value ?>" <?php } ?><?php if ($input->default_v) { ?>placeholder="<?= $input->default_v ?>"<?php } ?> <?php include 'jsv.tpl'; ?> />
</div>
