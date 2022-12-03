<?php $v->layout("_theme"); ?>

<div class="app_formbox app_widget">

    <article class="app_signature app_signature_me radius">
        <p class="app_signature_header_subscription">
            <?php
            $subscription = (new \Source\Models\WaterApp\AppSubscription())->find("id = {$invoice->subscription_id}")->fetch();
            $apartment = (new \Source\Models\WaterApp\AppApartment())->find("id = {$subscription->apartment_id}")->fetch();
            $condominium = (new \Source\Models\WaterApp\AppCondominium())->find("id = {$apartment->condominium_id}")->fetch();
            ?>
            <span>
                Condomínio: <?= $condominium->name; ?> //
                <?php if (!$invoice->detail_1): ?>
                    Bl.: <?= $apartment->block; ?> - Ap.: <?= $apartment->number; ?> //
                    Hidrômetro nº: <?= $apartment->hydrometer; ?> //
                    Localização: <?= $apartment->location; ?>
                <?php else: ?>
                    Bl.: <?= $apartment->block; ?> - Ap.: <?= $apartment->number; ?>
                <?php endif ?>
            </span>
        </p>
        <header class="app_signature_me_header">

            <h1>Demonstrativo nº <?= str_replace("-", "", $invoice->due_at) . $apartment->block . $apartment->number; ?></h1>
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
            <li>
                <h2>Dados do Condomínio</h2>
            </li>
            <li><span>Consumo do condomínio:</span> <span><?= $invoice->consumption_condominium; ?> m<sup>3</sup></span></li>
            <li><span>Valor do condomínio:</span> <span>R$ <?= str_price($invoice->value_condominium); ?></span></li>
            <li><span>Valor por m<sup>3</sup>:</span> <span>R$ <?= str_price($invoice->value_per_m3); ?></span></li>
            <li><span>Taxa de inadimplência:</span> <span>R$ <?= str_price($invoice->tax); ?></span></li>
            <li><span>Área comum:</span> <span>R$ <?= str_price($invoice->common_area); ?></span></li>
            <li>
                <h2>Dados da Unidade</h2>
            </li>
            <li><span>Data da leitura:</span> <span><?= date_fmt($invoice->due_at, "d/m/Y"); ?></span></li>
            <li><span>Leitura:</span> <span><?= $invoice->reading; ?></li>
            <li><span>Leitura estimada</span> <span><?= $invoice->estimated_reading; ?></li>
            <li><span>Consumo individual:</span> <span><?= $invoice->consumption; ?> m<sup>3</sup></span></li>
            <li><span>Valor individual:</span> <span>R$ <?= str_price($invoice->individual); ?></span></li>
            <?php if (!empty($invoice->detail_1)): ?>
                <li>
                    <h2>Leitura detalhada</h2>
                </li>
                <?php
                $list = explode("/", $invoice->detail_1);
                $hidrometer = isset($list[0]) ? $list[0] : '-';
                $location = isset($list[1]) ? $list[1] : '-';
                $current_reading = isset($list[2]) ? str_float((float) $list[2]) : '0';
                $estimated_reading = isset($list[3]) ? str_float((float) $list[3]) : '0';
                $consumption = isset($list[4]) ? str_float((float) $list[4]) : '0';
                echo "<li><span>Hidrômetro:</span><span>{$hidrometer}</span></li>"
                . "<li><span>Localização:</span><span>{$location}</span></li>"
                . "<li><span>Leitura atual:</span><span>{$current_reading}</span></li> "
                . "<li><span>Leitura estimada:</span> <span>{$estimated_reading}</span></li>"
                . "<li><span >Consumo:</span><span>{$consumption} m<sup>3</sup></span></li>"
                ?>
            <?php endif; ?>
            <?php if (!empty($invoice->detail_2)): ?>         
                <?php
                $list = explode("/", $invoice->detail_2);
                $hidrometer = isset($list[0]) ? $list[0] : '-';
                $location = isset($list[1]) ? $list[1] : '-';
                $current_reading = isset($list[2]) ? str_float((float) $list[2]) : '0';
                $estimated_reading = isset($list[3]) ? str_float((float) $list[3]) : '0';
                $consumption = isset($list[4]) ? str_float((float) $list[4]) : '0';
                echo "<li style='border-top: 1px solid rgba(136,136,136, 0.5)'><span>Hidrômetro:</span><span>{$hidrometer}</span></li>"
                . "<li><span>Localização:</span><span>{$location}</span></li>"
                . "<li><span>Leitura atual:</span><span>{$current_reading}</span></li> "
                . "<li><span>Leitura estimada:</span> <span>{$estimated_reading}</span></li>"
                . "<li><span >Consumo:</span><span>{$consumption} m<sup>3</sup></span></li>"
                ?>
            <?php endif; ?>
            <?php if (!empty($invoice->detail_3)): ?>         
                <?php
                $list = explode("/", $invoice->detail_3);
                $hidrometer = isset($list[0]) ? $list[0] : '-';
                $location = isset($list[1]) ? $list[1] : '-';
                $current_reading = isset($list[2]) ? str_float((float) $list[2]) : '0';
                $estimated_reading = isset($list[3]) ? str_float((float) $list[3]) : '0';
                $consumption = isset($list[4]) ? str_float((float) $list[4]) : '0';
                echo "<li style='border-top: 1px solid rgba(136,136,136, 0.5)'><span>Hidrômetro:</span><span>{$hidrometer}</span></li>"
                . "<li><span>Localização:</span><span>{$location}</span></li>"
                . "<li><span>Leitura atual:</span><span>{$current_reading}</span></li> "
                . "<li><span>Leitura estimada:</span> <span>{$estimated_reading}</span></li>"
                . "<li><span >Consumo:</span><span>{$consumption} m<sup>3</sup></span></li>"
                ?>
            <?php endif; ?>
            <?php if (!empty($invoice->detail_4)): ?>         
                <?php
                $list = explode("/", $invoice->detail_4);
                $hidrometer = isset($list[0]) ? $list[0] : '-';
                $location = isset($list[1]) ? $list[1] : '-';
                $current_reading = isset($list[2]) ? str_float((float) $list[2]) : '0';
                $estimated_reading = isset($list[3]) ? str_float((float) $list[3]) : '0';
                $consumption = isset($list[4]) ? str_float((float) $list[4]) : '0';
                echo "<li style='border-top: 1px solid rgba(136,136,136, 0.5)'><span>Hidrômetro:</span><span>{$hidrometer}</span></li>"
                . "<li><span>Localização:</span><span>{$location}</span></li>"
                . "<li><span>Leitura atual:</span><span>{$current_reading}</span></li> "
                . "<li><span>Leitura estimada:</span> <span>{$estimated_reading}</span></li>"
                . "<li><span >Consumo:</span><span>{$consumption} m<sup>3</sup></span></li>"
                ?>
            <?php endif; ?>
            <?php if (!empty($invoice->detail_5)): ?>         
                <?php
                $list = explode("/", $invoice->detail_5);
                $hidrometer = isset($list[0]) ? $list[0] : '-';
                $location = isset($list[1]) ? $list[1] : '-';
                $current_reading = isset($list[2]) ? str_float((float) $list[2]) : '0';
                $estimated_reading = isset($list[3]) ? str_float((float) $list[3]) : '0';
                $consumption = isset($list[4]) ? str_float((float) $list[4]) : '0';
                echo "<li style='border-top: 1px solid rgba(136,136,136, 0.5)'><span>Hidrômetro:</span><span>{$hidrometer}</span></li>"
                . "<li><span>Localização:</span><span>{$location}</span></li>"
                . "<li><span>Leitura atual:</span><span>{$current_reading}</span></li> "
                . "<li><span>Leitura estimada:</span> <span>{$estimated_reading}</span></li>"
                . "<li><span >Consumo:</span><span>{$consumption} m<sup>3</sup></span></li>"
                ?>
            <?php endif; ?>
            <?php if (!empty($invoice->detail_6)): ?>         
                <?php
                $list = explode("/", $invoice->detail_6);
                $hidrometer = isset($list[0]) ? $list[0] : '-';
                $location = isset($list[1]) ? $list[1] : '-';
                $current_reading = isset($list[2]) ? str_float((float) $list[2]) : '0';
                $estimated_reading = isset($list[3]) ? str_float((float) $list[3]) : '0';
                $consumption = isset($list[4]) ? str_float((float) $list[4]) : '0';
                echo "<li style='border-top: 1px solid rgba(136,136,136, 0.5)'><span>Hidrômetro:</span><span>{$hidrometer}</span></li>"
                . "<li><span>Localização:</span><span>{$location}</span></li>"
                . "<li><span>Leitura atual:</span><span>{$current_reading}</span></li> "
                . "<li><span>Leitura estimada:</span> <span>{$estimated_reading}</span></li>"
                . "<li><span >Consumo:</span><span>{$consumption} m<sup>3</sup></span></li>"
                ?>
            <?php endif; ?>
            <?php if (!empty($invoice->detail_7)): ?>         
                <?php
                $list = explode("/", $invoice->detail_7);
                $hidrometer = isset($list[0]) ? $list[0] : '-';
                $location = isset($list[1]) ? $list[1] : '-';
                $current_reading = isset($list[2]) ? str_float((float) $list[2]) : '0';
                $estimated_reading = isset($list[3]) ? str_float((float) $list[3]) : '0';
                $consumption = isset($list[4]) ? str_float((float) $list[4]) : '0';
                echo "<li style='border-top: 1px solid rgba(136,136,136, 0.5)'><span>Hidrômetro:</span><span>{$hidrometer}</span></li>"
                . "<li><span>Localização:</span><span>{$location}</span></li>"
                . "<li><span>Leitura atual:</span><span>{$current_reading}</span></li> "
                . "<li><span>Leitura estimada:</span> <span>{$estimated_reading}</span></li>"
                . "<li><span >Consumo:</span><span>{$consumption} m<sup>3</sup></span></li>"
                ?>
            <?php endif; ?>
            <?php if (!empty($invoice->detail_8)): ?>         
                <?php
                $list = explode("/", $invoice->detail_8);
                $hidrometer = isset($list[0]) ? $list[0] : '-';
                $location = isset($list[1]) ? $list[1] : '-';
                $current_reading = isset($list[2]) ? str_float((float) $list[2]) : '0';
                $estimated_reading = isset($list[3]) ? str_float((float) $list[3]) : '0';
                $consumption = isset($list[4]) ? str_float((float) $list[4]) : '0';
                echo "<li style='border-top: 1px solid rgba(136,136,136, 0.5)'><span>Hidrômetro:</span><span>{$hidrometer}</span></li>"
                . "<li><span>Localização:</span><span>{$location}</span></li>"
                . "<li><span>Leitura atual:</span><span>{$current_reading}</span></li> "
                . "<li><span>Leitura estimada:</span> <span>{$estimated_reading}</span></li>"
                . "<li><span >Consumo:</span><span>{$consumption} m<sup>3</sup></span></li>"
                ?>
            <?php endif; ?>
            <?php if (!empty($invoice->detail_9)): ?>         
                <?php
                $list = explode("/", $invoice->detail_9);
                $hidrometer = isset($list[0]) ? $list[0] : '-';
                $location = isset($list[1]) ? $list[1] : '-';
                $current_reading = isset($list[2]) ? str_float((float) $list[2]) : '0';
                $estimated_reading = isset($list[3]) ? str_float((float) $list[3]) : '0';
                $consumption = isset($list[4]) ? str_float((float) $list[4]) : '0';
                echo "<li style='border-top: 1px solid rgba(136,136,136, 0.5)'><span>Hidrômetro:</span><span>{$hidrometer}</span></li>"
                . "<li><span>Localização:</span><span>{$location}</span></li>"
                . "<li><span>Leitura atual:</span><span>{$current_reading}</span></li> "
                . "<li><span>Leitura estimada:</span> <span>{$estimated_reading}</span></li>"
                . "<li><span >Consumo:</span><span>{$consumption} m<sup>3</sup></span></li>"
                ?>
            <?php endif; ?>
            <?php if (!empty($invoice->detail_10)): ?>         
                <?php
                $list = explode("/", $invoice->detail_10);
                $hidrometer = isset($list[0]) ? $list[0] : '-';
                $location = isset($list[1]) ? $list[1] : '-';
                $current_reading = isset($list[2]) ? str_float((float) $list[2]) : '0';
                $estimated_reading = isset($list[3]) ? str_float((float) $list[3]) : '0';
                $consumption = isset($list[4]) ? str_float((float) $list[4]) : '0';
                echo "<li style='border-top: 1px solid rgba(136,136,136, 0.5)'><span>Hidrômetro:</span><span>{$hidrometer}</span></li>"
                . "<li><span>Localização:</span><span>{$location}</span></li>"
                . "<li><span>Leitura atual:</span><span>{$current_reading}</span></li> "
                . "<li><span>Leitura estimada:</span> <span>{$estimated_reading}</span></li>"
                . "<li><span >Consumo:</span><span>{$consumption} m<sup>3</sup></span></li>"
                ?>
            <?php endif; ?>
            <?php if ($invoice->observation): ?>            
                <li><h2>Observações</h2></li>
                <li><span style="flexbox: 100%"><?= $invoice->observation; ?></span></li>
            <?php endif; ?>
            <li><h3>Total: R$ <?= str_price($invoice->value); ?></h3></li>
        </ul>
    </article>
    <div class="al-center">
        <div class="app_formbox_actions">
            <a class="btn_back transition radius icon-sign-in" href="<?= url_back(); ?>" title="Voltar">Voltar</a>
        </div>
    </div>
</form>
</div>