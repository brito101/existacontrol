<?php $v->layout("_theme"); ?>
<div class="app_main_box">
    <section class="app_main_left">
        <article class="app_widget">
            <header class="app_widget_title">
                <h2 class="icon-bar-chart">Controle</h2>
            </header>
            <div id="control"></div>
        </article>

        <div class="app_main_left_fature">
            <article class="app_widget app_widget_balance">
                <header class="app_widget_title">
                    <h2 class="icon-bar-chart">Consumo em m<sup>3</sup>:</h2>
                </header>
                <?php if ($user->level != 2): ?>
                    <div class="app_widget_content">
                        <?php if (!empty($consumptions)): ?>
                            <?php foreach ($consumptions as $consumption): ?>
                                <?= $v->insert("views/consumption", ["invoice" => $consumption->data()]); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="message success al-center icon-check-square-o">
                                No momento, não existem dados de consumo para consulta.
                            </div>
                        <?php endif; ?>
                        <a href="<?= url("app/historico"); ?>" title="Histórico"
                           class="app_widget_more transition">+ Consumos</a>
                    </div>
                <?php else: ?>
                    <div class="app_widget_content">
                        <?php if (!empty($consumptions)): ?>
                            <?php foreach ($consumptions as $consumption): ?>
                                <?= $v->insert("views/consumptionCondominium", ["invoice" => $consumption->data()]); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="message success al-center icon-check-square-o">
                                No momento, não existem dados de consumo para consulta.
                            </div>
                        <?php endif; ?>
                        <a href="<?= url("app/historico-condominio"); ?>" title="Histórico Condomínio"
                           class="app_widget_more transition">+ Consumos</a>
                    </div>
                <?php endif; ?>
            </article>

            <article class="app_widget app_widget_balance">
                <header class="app_widget_title">
                    <h2 class="icon-money" title="Valor total: individual + área comum + taxa de inadimplência">Valor Total:</h2>
                </header>
                <?php if ($user->level != 2): ?>
                    <div class="app_widget_content">
                        <?php if (!empty($invoices)): ?>
                            <?php foreach ($invoices as $invoice): ?>
                                <?= $v->insert("views/value", ["invoice" => $invoice->data()]); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="message error al-center icon-check-square-o">
                                No momento, não existem valores para consulta.
                            </div>
                        <?php endif; ?>
                        <a href="<?= url("app/historico"); ?>" title="Histórico"
                           class="app_widget_more transition">+ Valores</a>
                    </div>
                <?php else: ?>
                    <div class="app_widget_content">
                        <?php if (!empty($invoices)): ?>
                            <?php foreach ($invoices as $invoice): ?>
                                <?= $v->insert("views/valueCondominium", ["invoice" => $invoice->data()]); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="message error al-center icon-check-square-o">
                                No momento, não existem valores para consulta.
                            </div>
                        <?php endif; ?>
                        <a href="<?= url("app/historico-condominio"); ?>" title="Histórico Condomínio"
                           class="app_widget_more transition">+ Valores</a>
                    </div>
                <?php endif; ?>
            </article>
        </div>
    </section>

    <section class="app_main_right">
        <article
            class="app_flex app_wallet gradient-blue">
            <header class="app_flex_title">
                <h2 class="radius">Média de valores por leitura</h2>
            </header>
            <?php if ($user->level != 2): ?>
                <p class="app_flex_amount" title="Valor total: individual + área comum + taxa de inadimplência">
                    R$ <?= str_price(($avarage->value ?? 0)); ?></p>
                <p class="app_flex_balance">
                    <span class="expense">Valor Individual: R$ <?= str_price(($avarage->individual ?? 0)); ?></span>
                    <span class="income">Consumo Individual: <?= number_format(($avarage->consumption ?? 0), 2, ",", "."); ?> m<sup>3</sup></span>
                </p>
            <?php else: ?>
                <p class="app_flex_amount" title="Valor total">
                    R$ <?= str_price(($avarage->value ?? 0)); ?></p>
                <p class="app_flex_balance">
                    <span class="income">Consumo: <?= number_format(($avarage->consumption ?? 0), 2, ",", "."); ?> m<sup>3</sup></span>
                </p>
            <?php endif; ?>
        </article>

        <section class="app_widget app_widget_blog"> 
            <header class="app_widget_title">
                <h2 class="icon-star-o">Nossos Serviços:</h2>
            </header>
            <div class="app_widget_content">
                <?php if (!empty($services)): ?>
                    <?php foreach ($services as $service): ?>
                        <article class="app_widget_blog_article">
                            <div class="thumb">
                                <img alt="<?= $service->title; ?>" title="<?= $service->title; ?>"
                                     src="<?= image($service->cover, 300); ?>"/>
                            </div>
                            <h3 class="title">
                                <a target="_blank" href="<?= url("/servicos/{$service->uri}"); ?>"
                                   title="<?= $service->title; ?>"><?= str_limit_chars($service->title, 50); ?></a>
                            </h3>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
                <a target="_blank" href="<?= url("/servicos"); ?>" title="Serviços"
                   class="app_widget_more transition">Ver Mais...</a>
            </div>
        </section>
    </section>
</div>
<?php if ($user->level != 2): ?>
    <?php $v->start("scripts"); ?>
    <script type="text/javascript">
        $(function () {

            Highcharts.setOptions({
                lang: {
                    decimalPoint: ',',
                    thousandsSep: '.'
                }
            });

            var chart = Highcharts.chart('control', {
                chart: {
                    type: 'line',
                    spacingBottom: 0,
                    spacingTop: 5,
                    spacingLeft: 0,
                    spacingRight: 0,
                    height: (9 / 16 * 100) + '%'
                },
                title: null,
                xAxis: {
                    categories: [<?= $chart->categories; ?>],
                    minTickInterval: 1
                },
                yAxis: {
                    allowDecimals: true,
                    title: null
                },
                tooltip: {
                    shared: true,
                    valueDecimals: 2
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    areaspline: {
                        fillOpacity: 0.5
                    }
                },
                series: [{
                        name: 'Consumo (m³)',
                        data: [<?= $chart->income; ?>],
                        color: '#00BFFF',
                        lineColor: '#4169E1'
                    }, {
                        name: 'Valor Individual (R$)',
                        data: [<?= $chart->expense; ?>],
                        color: '#00FF7F',
                        lineColor: '#3CB371'
                    }, {
                        name: 'Área comum + Taxa de inadimplência (R$)',
                        data: [<?= $chart->tax; ?>],
                        color: '#FA8072',
                        lineColor: '#B22222'
                    }]
            });


        });
    </script>
    <?php $v->end(); ?>
<?php else: ?>
    <?php $v->start("scripts"); ?>
    <script type="text/javascript">
        $(function () {

            Highcharts.setOptions({
                lang: {
                    decimalPoint: ',',
                    thousandsSep: '.'
                }
            });

            var chart = Highcharts.chart('control', {
                chart: {
                    type: 'line',
                    spacingBottom: 0,
                    spacingTop: 5,
                    spacingLeft: 0,
                    spacingRight: 0,
                    height: (9 / 16 * 100) + '%'
                },
                title: null,
                xAxis: {
                    categories: [<?= $chart->categories; ?>],
                    minTickInterval: 1
                },
                yAxis: {
                    allowDecimals: true,
                    title: null
                },
                tooltip: {
                    shared: true,
                    valueDecimals: 2
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    areaspline: {
                        fillOpacity: 0.5
                    }
                },
                series: [{
                        name: 'Consumo (m³)',
                        data: [<?= $chart->income; ?>],
                        color: '#00BFFF',
                        lineColor: '#4169E1'
                    }, {
                        name: 'Valor (R$)',
                        data: [<?= $chart->expense; ?>],
                        color: '#00FF7F',
                        lineColor: '#3CB371'
                    }, {
                        name: 'Área comum (R$)',
                        data: [<?= $chart->tax; ?>],
                        color: '#FA8072',
                        lineColor: '#B22222'
                    }]
            });


        });
    </script>
    <?php $v->end(); ?>
<?php endif; ?>
