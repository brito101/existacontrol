<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/control/sidebar.php"); ?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class="icon-bar-chart">Controle</h2>
    </header>

    <div class="dash_content_app_box">
        <div class="app_control_home">
            <section class="app_control_home_stats">
                <article class="radius">
                    <h4 class="icon-map-marker">Condomínios</h4>
                    <p><?= str_pad($stats->condominiuns, 5, 0, 0); ?></p>
                </article>
                <article class="radius">
                    <h4 class="icon-home">Apartamentos</h4>
                    <p><?= str_pad($stats->apartments, 5, 0, 0); ?></p>
                </article>
                <article class="radius">
                    <h4 class="icon-pencil-square-o">Assinantes</h4>
                    <p><?= str_pad($stats->subscriptions, 5, 0, 0); ?></p>
                </article>
                <article class="radius">
                    <h4 class="icon-folder-o">Demonstrativos</h4>
                    <p><?= str_pad($stats->invoices, 5, 0, 0); ?></p>
                </article>
            </section>

            <section class="app_control_subs radius">
                <h3 class="icon-heartbeat">Assinantes:</h3>
                <?php if (!$subscriptions): ?>
                    <div class="message info icon-info">Ainda não existem usuários em seu APP, assim que eles
                        começarem a chegar você verá os mais recentes aqui. Esperamos que seja em breve :)
                    </div>
                <?php else: ?>
                    <?php foreach ($subscriptions as $subscription): ?>
                        <article class="subscriber">
                            <p><?= date_fmt($subscription->created_at, "d/m/Y \- H\hm"); ?>
                                - <?= $subscription->user()->fullName(); ?> - 
                                <?= $subscription->plan()->name; ?>  
                                (<?= ($subscription->apartment()->block) ?? null; ?> - <?= ($subscription->apartment()->number) ?? null; ?>)</p>
                            <p><?= ($subscription->status == "active" ? "Ativo" : "Inativo"); ?></p>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </div>
    </div>
</section>