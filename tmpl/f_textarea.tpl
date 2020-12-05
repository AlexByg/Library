<div class="form-group">
    <label for="<?= $input->name ?>"><?= $input->label ?></label>
    <textarea rows="<?= $input->rows ?>" class="form-control" id="<?= $input->name ?>" name="<?= $input->name ?>" style="resize: none" <?php include 'jsv.tpl'; ?>><?= $input->value ?></textarea>
</div>
