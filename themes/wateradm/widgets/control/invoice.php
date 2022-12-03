<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/control/sidebar.php"); ?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class="icon-folder-open-o">Demonstrativo ID: <?= $invoice->id; ?></h2>
    </header>

    <?php if (!empty($invoice) && $invoice->cover): ?>
        <img class="radius" style="width: 300px; margin-top: 30px" src="<?= image($invoice->cover, 600, 600); ?>"/>
    <?php endif; ?>

    <div class="dash_content_app_box">
        <form class="app_form" action="<?= url("/admin/controle/demonstrativo/{$invoice->id}"); ?>" method="post">
            <!--ACTION SPOOFING-->
            <input type="hidden" name="action" value="update"/>
            <label class="label">
                <span class="legend">Foto:</span>
                <input type="file" name="cover"/>
            </label>

            <div class="app_form_footer">
                <button class="btn btn-blue icon-check-square-o">Salvar Foto</button>                    
            </div>
        </form>
    </div>


</section>