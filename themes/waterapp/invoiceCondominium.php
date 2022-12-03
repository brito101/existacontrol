<?php $v->layout("_theme"); ?>

<div class="app_formbox app_widget">

    <article class="app_signature app_signature_me radius">
        <p class="app_signature_header_subscription">
            <?php
            $condominium = (new \Source\Models\WaterApp\AppCondominium())->find("id = {$invoice->condominium_id}")->fetch();
            ?>
            <span>
                Condomínio: <?= $condominium->name; ?> // <?= $condominium->address; ?>
            </span>
        </p>
        <header class="app_signature_me_header">
            <h1>Relatório de Consumo Global nº <?= $invoice->id; ?></h1>
        </header>
        <ul class="app_signature_detail radius">
            <?php if (!empty($invoice) && $invoice->cover): ?>
                <li><span>Fotografia da Leitura:</span> 
                    <span>
                        <div >
                            <img class="radius" style="width: 100px;" src="<?= image($invoice->cover, 600, 600); ?>"/>
                        </div>
                    </span>
                </li>
            <?php endif; ?>
            <li><span>Conta recebida em:</span> <span><?= date_fmt($invoice->due_at, "d/m/Y"); ?></span></li>
            <li><span>Relatório criado em:</span> <span><?= date_fmt($invoice->report_at, "d/m/Y"); ?></span></li>
            <li><span>Unidades:</span> <span><?= $units; ?></span></li>
            <li>
                <h2>Dados da Concessionária</h2>
            </li>
            <li><span>Concessionária:</span> <span><?= $condominium->dealership; ?></span></li>
            <li><span>Período da leitura:</span> <span><?= $invoice->description_dealership ?></span></li>
            <li><span>Vencimento da conta em:</span> <span><?= date_fmt($invoice->expiration, "d/m/Y"); ?></span></li>
            <li><span>Consumo total:</span> <span><?= $invoice->consumption ?> m<sup>3</sup></span></li>
            <li><span>Valor total:</span> <span>R$ <?= str_price($invoice->value); ?></span></li>
            <li><span>Preço por m<sup>3</sup> (variável):</span> <span>R$ <?= str_price($invoice->value_per_m3); ?></span></li>
            <li><span>Faixa de cobrança atingida:</span> <span> <?= $invoice->charge; ?></span></li>
            <li>
                <h2>Dados da Exista</h2>
            </li>
            <li><span>Período da leitura:</span> <span><?= $invoice->description; ?></span></li>
            <li><span>Soma do consumo medido:</span> <span><?= $invoice->sum_consumption ?> m<sup>3</sup></span></li>
            <li><span>Valor a individualizar (por m<sup>3</sup>):</span> <span>R$ <?= str_price($invoice->individual_value); ?></span></li>
            <li><span>Valor proporcional esgoto m<sup>3</sup>:</span> <span>R$ <?= str_price($invoice->sewer); ?></span></li>
            <li><span>Consumo área comum:</span> <span><?= $invoice->reading_common_area; ?> m<sup>3</sup></span></li>
            <li><span>Área comum:</span> <span>R$ <?= str_price($invoice->common_area); ?></span></li>
            <li><span>Previsão orçamentária:</span> <span>R$ <?= str_price($invoice->budget); ?></span></li>
            <li><span>Diferença de arrecadação:</span> <span>R$ <?= str_price($invoice->tax_revenues); ?></span></li>
            <li><h3>Tipos de Consumo</h3></li>
            <li id="pie_chart" style="margin: 0 auto;"></li>
            <li><span>Observações:</span> <span><?= $invoice->observation; ?></span></li>    
        </ul>
    </article>
    <div class="al-center">
        <div class="app_formbox_actions">
            <a class="btn_back transition radius icon-sign-in" href="<?= url_back(); ?>" title="Voltar">Voltar</a>
        </div>
    </div>
</form>
</div>

<?php $v->start("scripts"); ?>
<script type="text/javascript">
    $(function () {

        Highcharts.setOptions({
            lang: {
                decimalPoint: ',',
                thousandsSep: '.'
            }
        });
        var chart = Highcharts.chart('pie_chart', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                responsive: true,
                height: 50 + '%'
            },
            title: null,
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                areaspline: {
                    fillOpacity: 0.5
                },
                pie: {
                    innerSize: 60 + '%',
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<p>{point.name}</p>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                    name: 'Tipos de Consumo',
                    colorByPoint: true,
                    data: [{
                            name: 'Consumo Individual ',
                            y:<?= $invoice->sum_consumption; ?>,
                            color: '#4040FF'
                        }, {
                            name: 'Área comum',
                            y: <?= $invoice->reading_common_area; ?>,
                            color: '#FFAA00'
                        }]
                }]
        });
    });
</script>
<?php $v->end(); ?>