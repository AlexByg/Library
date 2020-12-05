<div class="form-group">
    <div class="d-flex flex-column">
        <label for="<?= $input->name ?>"><?= $input->label ?></label>
        <input type="file" name="<?= $input->name ?>" id="<?= $input->name ?>" <?php include 'jsv.tpl'; ?> />
    </div>
</div>
