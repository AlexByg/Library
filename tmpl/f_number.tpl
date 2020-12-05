<div class="form-group">
    <label for="<?= $input->name ?>"><?= $input->label ?></label>
    <input class="form-control" id="<?= $input->name ?>" type="<?= $input->type ?>" name="<?= $input->name ?>" <?php if ($input->max_v) { ?>max="<?= $input->max_v ?>"<?php } ?> <?php if ($input->min_v !== '') { ?>min="<?= $input->min_v ?>"<?php } ?> <?php if ($input->step) { ?>step="<?= $input->step ?>"<?php } ?> <?php if ($input->value) { ?>value="<?= $input->value ?>"<?php } ?><?php include 'jsv.tpl'; ?> />
</div>
