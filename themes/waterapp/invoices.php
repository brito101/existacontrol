<?php $v->layout("_theme"); ?>
<div class="app_formbox moradores app_widget">
    <section class="app_launch_box moradores">
        <?php if (!$invoices): ?>
            <?php if (empty($filter)): ?>
                <div class="message info icon-info">Ainda não existe histórico para consulta.
                </div>
            <?php else: ?>
                <div class="message info icon-info">Não existe histórico para a data selecionada.
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="app_launch_item moradores header">
                <p class="desc">Descrição</p>
                <p class="price">Leitura</p>
                <p class="price">Data</p>
                <p class="price">Leitura anterior</p>
                <p class="price">Data anterior</p>
                <p class="category">Consumo</p>
                <p class="date">Valor Total</p>
            </div>
            <?php foreach ($invoices as $invoice): ?>
                <article class="app_launch_item moradores">
                    <p class="desc app_invoice_link transition">
                        <a title="<?= $invoice->description; ?>"
                           href="<?= url("/app/demonstrativo/{$invoice->id}"); ?>">
                               <?php if ($user->level != 2): ?>
                                Demonstrativo nº <?= $invoice->id ?>

                            <?php else: ?>
                                <?php
                                $subscription = (new Source\Models\WaterApp\AppSubscription())
                                        ->find("id = {$invoice->subscription_id}")
                                        ->fetch();
                                $apartment = (new Source\Models\WaterApp\AppApartment())
                                        ->find("id = {$subscription->apartment_id}")
                                        ->fetch();
                                ?>
                                Dem. nº <?= $invoice->id ?> -
                                Bl: <?= $apartment->block; ?> /
                                Ap: <?= $apartment->number; ?>          
                            <?php endif; ?>
                        </a>     
                    </p>
                    <p class="price"><?= $invoice->reading; ?></p>
                    <p class="price"><?= date_fmt($invoice->due_at, "d/m/Y"); ?></p>
                    <p class="price">
                        <?php
                        $interval = (date('Y-m-d', strtotime('-1 month', strtotime($invoice->due_at))));
                        $last_invoice = (new Source\Models\WaterApp\AppInvoice())
                                        ->find("subscription_id = {$invoice->subscription_id} AND MONTH(due_at) = MONTH('{$interval}') AND YEAR(due_at) = YEAR('{$interval}')")->fetch();
                        echo $last_invoice->reading ?? "-";
                        ?>
                    </p>
                    <p class="price">
                        <?php
                        if ($last_invoice) {
                            echo date_fmt($last_invoice->due_at, "d/m/Y");
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
