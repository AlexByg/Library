<head>
    <?php foreach ($meta as $m) { ?>
        <meta <?php if ($m->http_equiv) { ?>http-equiv<?php } else { ?>name<?php } ?>="<?= $m->name ?>" content="<?= $m->content ?>" />
    <?php } ?>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <?php foreach ($css as $href) { ?>
        <link href="<?= $href ?>" rel="stylesheet" type="text/css" />
    <?php }	?>
    <?php foreach ($js as $src) { ?>
        <script src="<?= $src ?>"></script>
    <?php } ?>
    <?php if ($favicon) { ?>
        <link href="<?= $favicon ?>" rel="shortcut icon"  type="image/x-icon" />
    <?php } ?>
    <title><?= $title ?></title>
</head>
