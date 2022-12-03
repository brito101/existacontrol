<?php $v->layout("_theme"); ?>
<div class="app_formbox app_widget">
    <section class="app_launch_box">
        <?php if (!$invoices): ?>
            <?php if (empty($filter)): ?>
                <div class="message info icon-info">Ainda não existe histórico para consulta.
                </div>
            <?php else: ?>
                <div class="message info icon-info">Não existe histórico para a data selecionada.
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="app_launch_item header">
                <p class="desc_balance">Descrição</p>
                <p class="price">Data</p>
                <p class="date">Data anterior</p>
                <p class="category">Consumo</p>
                <p class="date">Valor Total</p>
            </div>
            <?php foreach ($invoices as $invoice): ?>
                <article class="app_launch_item">
                    <p class="desc_balance app_invoice_link transition">
                        <a title="<?= $invoice->description; ?>"
                           href="<?= url("/app/demonstrativo-condominio/{$invoice->id}"); ?>">
                            Relatório Global nº <?= $invoice->id ?>
                        </a>     
                    </p>
                    <p class="price"><?= date_fmt($invoice->month_ref, "d/m/Y"); ?></p>
                        <?php
                       $interval = (date('Y-m-d', strtotime('-1 month', strtotime($invoice->month_ref))));
                       $last_invoice = (new Source\Models\WaterApp\AppInvoiceCondominium())
                                    ->find("condominium_id = {$invoice->condominium_id} AND MONTH(month_ref) = MONTH('{$interval}') AND YEAR(month_ref) = YEAR('{$interval}')")->fetch();
                    
                        ?>
                    <p class="date">
                        <?php
                        if ($last_invoice) {
                            echo date_fmt($last_invoice->month_ref, "d/m/Y");
                        } else {
                            echo "-";
                        }
                        ?>
                    </p>
                    <p class="category"><?= $invoice->consumption; ?> m<sup>3</sup></p>
                    <p class="date">R$ <?= str_price($invoice->value); ?></p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
        <?= $paginator; ?>
    </section>
</div>
