<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/control/sidebar.php"); ?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class="icon-map-marker">Condomínios</h2>
        <a class="icon-plus-circle btn btn-blue" href="<?= url("/admin/controle/condominio"); ?>">Novo Condomínio</a>
    </header>

    <div class="dash_content_app_box">
        <section>
            <div class="app_control_plans">
                <?php if (!$condominiums): ?>
                    <div class="message info icon-info">Ainda não existem condomínios cadastrados.</div>
                <?php else: ?>
                    <?php foreach ($condominiums as $condominium): ?>
                        <article class="radius">
                            <div>
                                <h4 class="icon-map-marker">
                                    <?= str_limit_words($condominium->name, 5); ?></h4>
                                <p><b>Usuários:</b> <?= str_pad($condominium->subscribers(), 3, 0, 0); ?></p><br>
                                <p><b>Status:</b> <?= ($condominium->status == "active" ? "Ativo" : "Inativo"); ?></p>
                            </div>  
                            <div class="actions">
                                <a class="icon-pencil btn btn-blue" title=""
                                   href="<?= url("/admin/controle/condominio/{$condominium->id}"); ?>">Atualizar</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?= $paginator; ?>
        </section>
    </div>
</section>