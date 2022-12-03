<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/control/sidebar.php"); ?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class="icon-briefcase"><?= (empty($search) ? "Gerentes" : "Pesquisa por {$search}"); ?></h2>
        <form action="<?= url("/admin/controle/gerentes"); ?>" method="post" class="app_search_form">
            <input type="text" name="s" value="<?= $search; ?>" placeholder="Pesquisar Usuário:">
            <button class="icon-search icon-notext"></button>
            <a style="margin-left: 10px;" class="icon-plus-circle btn btn-blue" href="<?= url("/admin/controle/gerente"); ?>">Novo Gerente</a>
        </form>
    </header>

    <div class="dash_content_app_box">
        <section>
            <div class="app_control_subscribers">
                <?php if (!$subscriptions): ?>
                    <?php if (empty($search)): ?>
                        <div class="message info icon-info">Ainda não existem usuários em seu APP, assim que eles
                            começarem a chegar você verá os mais recentes aqui. Esperamos que seja em breve :)
                        </div>
                    <?php else: ?>
                        <div class="message warning icon-warning">Não foram encontrados gerentes com NOME, SOBRENOME
                            ou EMAIL igual a <b><?= $search; ?></b>. Você pode tentar outros termos...
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <?php
                    foreach ($subscriptions as $subscription):
                        $photo = $subscription->user()->photo();
                        $userPhoto = ($photo ? image($photo, 300, 300) :
                                theme("/assets/images/avatar.jpg", CONF_VIEW_ADMIN));
                        ?>
                        <article class="radius">
                            <div class="cover" style="background-image: url(<?= $userPhoto; ?>);"></div>
                            <h4><?= str_limit_words($subscription->user()->fullName(), 3) ?></h4>
                            <p class="email"><?= substr( $subscription->user()->email , 0 , 20 ) . "..."; ?></p>
                            <p class="info">
                                Condomínio: <b><?= str_limit_words($subscription->plan()->name, 3); ?></b>
                            </p>
                            <p class="info">
                                Gerente desde <?= date_fmt($subscription->created_at, "d/m/y"); ?> <br>
                            </p>
                            <p class="info"><?= ($subscription->status == "active" ? "Ativo" : "Inativo"); ?></p>
                            <div class="actions">
                                <a class="icon-cog btn btn-blue" title=""
                                   href="<?= url("/admin/controle/gerente/{$subscription->id}"); ?>">Gerenciar</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?= $paginator; ?>
        </section>
    </div>
</section>