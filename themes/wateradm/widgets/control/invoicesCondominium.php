<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/control/sidebar.php"); ?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class="icon-bar-chart"><?= (empty($search) ? "Balanços" : "Pesquisa por {$search}"); ?></h2>
        <form action="<?= url("/admin/controle/balancos"); ?>" method="post" class="app_search_form">
            <input type="text" name="s" value="<?= $search; ?>" placeholder="Pesquisar Condomínio:">
            <button class="icon-search icon-notext"></button>
            <a style="margin-left: 10px;" class="icon-plus-circle btn btn-blue" href="<?= url("/admin/controle/balanco"); ?>">Novo Balanço</a>
        </form>       
    </header>

    <div class="dash_content_app_box">
        <section>
            <div class="app_control_subscribers">
                <?php if (!$invoices): ?>
                    <?php if (empty($search)): ?>
                        <div class="message info icon-info">Ainda não existem demonstrativos em seu APP, assim que elas
                            começarem a chegar você verá os mais recentes aqui. Esperamos que seja em breve :)
                        </div>
                    <?php else: ?>
                        <div class="message warning icon-warning">Não foram encontrados condomínios com NOME igual a 
                            <b><?= $search; ?></b>. Você pode tentar outros termos...
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <?php foreach ($invoices as $invoice): ?> 
                        <article class="radius">
                            <h4>Balanço nº <?= $invoice->id ?></h4>
                            <p class="email" style="padding-top: 10px;"><?= $invoice->description; ?></p>
                            <p class="info"><?= date_fmt($invoice->due_at, "d/m/Y"); ?></p>
                            <p class="info">
                                <?php                               
                                $condominium = (new Source\Models\WaterApp\AppCondominium)->find("id = {$invoice->condominium_id}")->fetch();
                                echo $condominium->name;
                                ?>
                            </p>

                            <p class="info">R$ <?= str_price($invoice->value); ?></p>
                            <div class="actions">
                                <a class="icon-pencil btn btn-blue" title=""
                                   href="<?= url("/admin/controle/balanco/{$invoice->id}"); ?>">Gerenciar</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?= $paginator; ?>
        </section>
    </div>
</section>