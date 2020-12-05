<div class="main col-md">
    <?php if (isset($hornav)) { ?><?=$hornav?><?php } ?>
    <?php if ($form_header) { ?><h1><?=$form_header?></h1><?php } ?>
    <?php if ($message) { ?><p class="font-weight-bold text-danger"><?=$message?></p><?php } ?>
    <div <?php if ($name) { ?>id="<?=$name?>"<?php } ?>>
        <form <?php if ($name) { ?>name="<?=$name?>"<?php } ?> action="<?=$action?>" method="<?=$method?>" <?php if ($check) { ?>onsubmit="return checkForm(this)"<?php } ?> <?php if ($enctype) { ?>enctype="<?=$enctype?>"<?php } ?>>
            <?php foreach ($inputs as $input) { ?>
            <?php include 'f_'.$input->type.'.tpl'; ?>
            <?php } ?>
        </form>
    </div>
</div>
