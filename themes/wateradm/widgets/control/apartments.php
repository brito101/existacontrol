<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/control/sidebar.php"); ?>
<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class="icon-home">Apartamentos</h2>
        <a class="icon-plus-circle btn btn-blue" href="<?= url("/admin/controle/apartamento"); ?>">Novo Apartamento</a>
    </header>
    <div class="dash_content_app_box">
        <section>
            <div class="app_control_plans">
                <?php if (!$apartments): ?>
                    <div class="message info icon-info">Ainda não existem apartamentos cadastrados.</div>
                <?php else: ?>
                    <?php foreach ($apartments as $apartment): ?>
                        <article class="radius">
                            <div>
                                <h4 class="icon-home">Bl: <?= $apartment->block; ?> 
                                    - Ap: <?= $apartment->number; ?>
                                     - <?= $apartment->condominiumName()->name; ?>
                                    </h4>
                                <p><b>Hidrômetro:</b> <?= $apartment->hydrometer; ?></p><br>
                                <p><b>Localização:</b> <?= $apartment->location?></p><br>
                                <p><b>Status:</b> <?= ($apartment->status == "active" ? "Ativo" : "Inativo"); ?></p>
                            </div>  
                            <div class="actions">
                                <a class="icon-pencil btn btn-blue" title=""
                                   href="<?= url("/admin/controle/apartamento/{$apartment->id}"); ?>">Atualizar</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?= $paginator; ?>
        </section>
    </div>
</section>