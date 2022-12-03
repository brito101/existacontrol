<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/control/sidebar.php"); ?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class="icon-folder-o"><?= (empty($search) ? "Demonstrativos" : "Pesquisa por {$search}"); ?></h2>
        <div class="custom_form">
            <form action="<?= url("/admin/controle/demonstrativos"); ?>" method="post" class="app_search_form app_search_form_upload">
                <input type="text" name="s" value="<?= $search; ?>" placeholder="Pesquisar Usuário:">
                <button class="icon-search icon-notext"></button>
            </form>

            <form action="<?= url("/admin/controle/importar"); ?>" method="post" class="app_search_form app_search_form_upload">
                <label class="icon-th-list">Envio de planilha</label>
                <input type="file" name="file">
                <button class="icon-upload icon-notext" title="Enviar"></button>
            </form>

            <form action="<?= url("/admin/controle/fotos"); ?>" method="post" enctype="multipart/form-data" 
                  class="app_search_form app_search_form_upload">
                <label class="icon-camera">Envio de fotos</label>
                <input type="file" accept="image/jpeg, image/jpg, image/png" name="images[]" multiple>
                <button class="icon-upload icon-notext" title="Enviar"></button>
            </form>

        </div>
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
                        <div class="message warning icon-warning">Não foram encontrados assinantes com NOME, SOBRENOME
                            ou EMAIL igual a <b><?= $search; ?></b>. Você pode tentar outros termos...
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <?php foreach ($invoices as $invoice): ?>
                        <article class="radius">
                            <h4>Demonstrativo ID: <?= $invoice->id ?></h4>
                            <p class="email" style="padding-top: 10px;"><?= $invoice->description; ?></p>
                            <p class="info"><?= date_fmt($invoice->due_at, "d/m/Y"); ?></p>
                            <p class="info">
                                <?=
                                str_limit_words((new Source\Models\User())->findById(
                                                ((new Source\Models\WaterApp\AppSubscription())->findById($invoice->subscription_id))->user_id)->fullName(), 5);
                                ?>
                            </p>
                            <p class="info">
                                <?=
                                (new Source\Models\WaterApp\AppCondominium())->findById(
                                        (new Source\Models\WaterApp\AppApartment())->findById(
                                                (new Source\Models\WaterApp\AppSubscription())->findById(
                                                        $invoice->subscription_id)->apartment_id)->condominium_id)->name
                                ;
                                ?>
                            </p>
                            <p class="info">
                                <?=
                                (new Source\Models\WaterApp\AppApartment())->findById(
                                        ((new Source\Models\WaterApp\AppSubscription())->findById($invoice->subscription_id))->apartment_id)->block;
                                ?> - 
                                <?=
                                (new Source\Models\WaterApp\AppApartment())->findById(
                                        ((new Source\Models\WaterApp\AppSubscription())->findById($invoice->subscription_id))->apartment_id)->number;
                                ?>
                            </p>
                            <p class="info">R$ <?= str_price($invoice->value); ?></p>
                            <div class="actions">
                                <a class="icon-camera btn btn-blue" title=""
                                   href="<?= url("/admin/controle/demonstrativo/{$invoice->id}"); ?>">Inserir Foto</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?= $paginator; ?>
        </section>
    </div>
</section>