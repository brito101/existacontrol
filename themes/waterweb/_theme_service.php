<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="mit" content="2020-03-24T14:43:59-03:00+169593">
        <meta name="viewport" content="width=device-width,initial-scale=1">

        <?= $head; ?>

        <link rel="icon" type="image/png" href="<?= theme("/assets/images/favicon.png"); ?>"/>
        <link rel="stylesheet" href="<?= theme("/assets/style.css"); ?>"/>

        <!--ANDROID-->
        <link rel="manifest" href="/../manifest.json"/>
        <meta name="theme_color" content="#f38321" />

        <!--IOS-->
        <meta name="apple-mobile-web-app-capable" content="true" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
        <link rel="apple-touch-icon" href="/../images/icons/icon-512x512.png"/>
        <link rel="apple-touch-startup-image" href="/../images/icons/icon-512x512.png"/>

    </head>
    <body>

        <div class="ajax_load">
            <div class="ajax_load_box">
                <div class="ajax_load_box_circle"></div>
                <p class="ajax_load_box_title">Aguarde, carregando...</p>
            </div>
        </div>

        <!--HEADER-->
        <header class="main_header gradient gradient-blue">
            <div class="container">
                <div class="main_header_logo">
                    <h1>
                        <a class="transition" title="Home" href="<?= url(); ?>">
                            <img src="<?= theme("/assets/images/exista-logotipo.png"); ?>" alt="Exista"/>
                        </a>
                    </h1>
                </div>

                <nav class="main_header_nav">
                    <span class="main_header_nav_mobile j_menu_mobile_open icon-menu icon-notext radius transition"></span>
                    <div class="main_header_nav_links j_menu_mobile_tab">
                        <span class="main_header_nav_mobile_close j_menu_mobile_close icon-error icon-notext transition"></span>
                        <a class="link transition radius" title="Home" href="<?= url(); ?>">Home</a>
                        <a class="link transition radius" title="Sobre Nós" href="<?= url("/sobre"); ?>">Sobre Nós</a>
                        <a class="link transition radius" title="Serviços" href="<?= url("/servicos"); ?>">Serviços</a>

                        <?php if (\Source\Models\Auth::user()): ?>
                            <a class="link login transition radius icon-check" title="Controlar"
                               href="<?= url("/app"); ?>">Controlar</a>
                           <?php else: ?>
                            <a class="link login transition radius icon-sign-in" title="Entrar"
                               href="<?= url("/entrar"); ?>">Entrar</a>
                           <?php endif; ?>

                    </div>
                </nav>
            </div>
        </header>

        <!--CONTENT-->
        <main class="main_content">
            <?= $v->section("content"); ?>
        </main>

        <?php if ($v->section("optout")): ?>
            <?= $v->section("optout"); ?>
        <?php else: ?>
            <article class="footer_optout">
                <div class="footer_optout_content content">
                    <img src="<?= theme("/assets/images/footer-existaControl.png"); ?>" width="100" alt="existaControl"/>
                    <h2>Comece a controlar suas medições de água agora mesmo</h2>
                    <p>É rápido, simples e seguro!</p>
                </div>
            </article>
        <?php endif; ?>

        <!--FOOTER-->
        <footer class="main_footer">
            <div class="container content">
                <section class="main_footer_content">
                    <article class="main_footer_content_item">
                        <h2>Sobre:</h2>
                        <a title="Termos de uso" href="<?= url("/termos"); ?>">Termos de uso</a>
                    </article>

                    <article class="main_footer_content_item">
                        <h2>Mais:</h2>
                        <a class="link transition radius" title="Home" href="<?= url(); ?>">Home</a>
                        <a class="link transition radius" title="Sobre Nós" href="<?= url("/sobre"); ?>">Sobre Nós</a>
                        <a class="link transition radius" title="Serviços" href="<?= url("/servicos"); ?>">Serviços</a>
                        <a class="link transition radius" title="Entrar" href="<?= url("/entrar"); ?>">Entrar</a>
                    </article>

                    <article class="main_footer_content_item">
                        <h2>Contato:</h2>
                        <p class="icon-phone"><b>Telefone:</b><br> +55 21 99976-1181 /  +55 21 96410-6562</p>
                        <p class="icon-envelope"><b>Email:</b><br> atendimento@existacontrol.com.br</p>
                        <p class="icon-map-marker"><b>Endereço:</b><br> Rio de Janeiro-RJ</p>
                        <p class="icon-suitcase"><b>CNPJ:</b><br> 027.130.219/0001-61</p>
                    </article>
                </section>
            </div>
        </footer>
        <script src="<?= theme("/assets/scripts.js"); ?>"></script>
        <?= $v->section("scripts"); ?>

        <script>
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('sw.js').then(function (registration) {
                    // Registration was successful
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function (err) {
                    // registration failed :(
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        </script>

    </body>
</html>