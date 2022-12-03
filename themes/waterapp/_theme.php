<!DOCTYPE html>
<html lang="pt-br">
    <head><meta charset="euc-jp">
        
        <meta name="viewport" content="width=device-width,initial-scale=1">

        <?= $head; ?>

        <link rel="stylesheet" href="<?= theme("/assets/style.css", CONF_VIEW_APP); ?>"/>
        <link rel="icon" type="image/png" href="<?= theme("/assets/images/favicon.png", CONF_VIEW_APP); ?>"/>
    </head>
    <body>

        <div class="ajax_load">
            <div class="ajax_load_box">
                <div class="ajax_load_box_circle"></div>
                <p class="ajax_load_box_title">Aguarde, carregando...</p>
            </div>
        </div>

        <div class="app">
            <header class="app_header">
                    <h1>
                        <img src="<?= theme("/assets/images/favicon-e.png"); ?>" width="30px" style="margin-bottom: -10px;"/>
                        <a href="<?= url("/app"); ?>" title="existaApp">xistaApp</a></h1>
                    <ul class="app_header_widget">
                        <li data-mobilemenu="open" class="app_header_widget_mobile radius transition icon-menu icon-notext"></li>
                    </ul>
                </header>

            <div class="app_box">
                <nav class="app_sidebar radius box-shadow">
                    <div data-mobilemenu="close"
                         class="app_sidebar_widget_mobile radius transition icon-error icon-notext"></div>

                    <div class="app_sidebar_user app_widget_title">
                        <span class="user">
                            <?php if (user()->photo()): ?>
                                <img class="rounded" alt="<?= user()->first_name; ?>" title="<?= user()->first_name; ?>"
                                     src="<?= image(user()->photo, 260, 260); ?>"/>
                                 <?php else: ?>
                                <img class="rounded" alt="<?= user()->first_name; ?>" title="<?= user()->first_name; ?>"
                                     src="<?= theme("/assets/images/avatar.jpg", CONF_VIEW_APP); ?>"/>
                                 <?php endif; ?>
                            <span><?= user()->first_name; ?></span>
                        </span>
                    </div>

                    <?= $v->insert("views/sidebar"); ?>
                </nav>

                <main class="app_main">
                    <div class="al-center"><?= flash(); ?></div>
                    <?= $v->section("content"); ?>
                </main>
            </div>

            <footer class="app_footer">
                <span>                   
                    <img src="<?= theme("/assets/images/favicon-e.png"); ?>" width="20px" style="margin: 0 -5px -8px 0;"/>
                    xistaControl - Todos os direitos reservados  &copy
                </span>
            </footer>

            <?= $v->insert("views/modals"); ?>
        </div>

        <script src="<?= theme("/assets/scripts.js", CONF_VIEW_APP); ?>"></script>
        <?= $v->section("scripts"); ?>

    </body>
</html>