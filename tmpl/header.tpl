<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="<?= URL::get('') ?>">Библиотека</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php foreach ($items_menu as $item) { ?>
                    <li <?php if ($item->link == $uri) { ?>class="nav-item active"<?php } ?>>
                        <a class="nav-link" href="<?= $item->link ?>"><?= $item->title ?></a>
                    </li>
                <?php } ?>
            </ul>
            <noscript>
                <div class="text-white mr-2">Для полноценной работы сайта включите JavaScript</div>
            </noscript>
            <?php if ($message) { ?><span class="text-danger font-weight-bold mr-2"><?= $message ?></span><?php }
            if ($action) { ?>
            <form class="form-inline my-2 my-lg-0" name="auth" action="<?= $action ?>" method="post">
                <input type="text" class="form-control mr-1 my-1" placeholder="Логин" name="login" />
                <input type="password" class="form-control mr-1 my-1" placeholder="Пароль" name="password" />
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="auth" value="auth">Войти</button>
            </form>
            <?php } else { ?>
                <div class="py-1">
                    <a href="<?= URL::get('logout') ?>" class="btn btn-outline-success ">Выход</a>
                </div>
            <?php } ?>
        </div>
    </nav>
</header>
