<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/control/sidebar.php"); ?>

<section class="dash_content_app">
    <?php if (!$invoiceCondominium): ?>
        <header class="dash_content_app_header">
            <h2 class="icon-bar-chart">Novo Balanço</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/controle/balanco"); ?>" method="post">
                <!--ACTION SPOOFING-->
                <input type="hidden" name="action" value="create"/>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Condomínio:</span>
                        <select name="condominium_id" required>
                            <?php
                            foreach ($condominiuns as $apartment):
                                $apartment_id = $apartment->id;
                                $selected = function ($value) use ($apartment_id) {
                                    return ($apartment_id == $value ? "selected" : "");
                                };
                                ?>
                                <option <?= $selected($apartment->id); ?> value="<?= $apartment->id; ?>">
                                    <?= $apartment->name; ?>
                                <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="label">
                        <span class="legend">*Data de referência para o gráfico:</span>
                        <input type="text" class="mask-date" name="month_ref" placeholder="dd/mm/yyyy" required/>
                    </label>   
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Data de recebimento da conta:</span>
                        <input type="text" class="mask-date" name="due_at" placeholder="dd/mm/yyyy" required/>
                    </label>   
                    <label class="label">
                        <span class="legend">*Data de criação do relatório:</span>
                        <input type="text" class="mask-date" name="report_at" placeholder="dd/mm/yyyy" required/>
                    </label>   
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Período da leitura da Exista:</span>
                        <input type="text" name="description" placeholder="Descrição" required/>
                    </label>       
                    <label class="label">
                        <span class="legend">*Período da leitura da Concessionária:</span>
                        <input type="text" name="description_dealership" placeholder="Descrição" required/>
                    </label>  
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Consumo medido pela concessionária:</span>
                        <input name="consumption" placeholder="Valor" required/>
                    </label> 
                    <label class="label">
                        <span class="legend">*Vencimento:</span>
                        <input type="text" class="mask-date" name="expiration" placeholder="dd/mm/yyyy" required/>
                    </label>   
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Total da concessionária em R$:</span>
                        <input name="value" placeholder="Valor" required/>
                    </label>   
                    <label class="label">
                        <span class="legend">*Valor por m<sup>3</sup>:</span>
                        <input name="value_per_m3" placeholder="Valor" required/>
                    </label>   
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Soma do consumo  medido pela Exista em m<sup>3</sup>:</span>
                        <input name="sum_consumption" placeholder="Valor" required/>
                    </label>   
                    <label class="label">
                        <span class="legend">*Valor a individualizar em R$ (por m<sup>3</sup>):</span>
                        <input name="individual_value" placeholder="Valor" required/>
                    </label>   
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Consumo da área comum em m<sup>3</sup>:</span>
                        <input name="reading_common_area" placeholder="Valor" required/>
                    </label>   
                    <label class="label">
                        <span class="legend">*Valor da área comum em R$:</span>
                        <input name="common_area" placeholder="Valor" required/>
                    </label>   
                </div>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">Valor proporcional esgoto 50%  m<sup>3</sup>:</span>
                        <input name="sewer" placeholder="Valor" />
                    </label>  
                    <label class="label">
                        <span class="legend">Faixa de cobrança atingida:</span>
                        <input name="charge" placeholder="Valor" />
                    </label> 
                </div>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">Previsão orçamentária</span>
                        <input name="budget" placeholder="Valor" />
                    </label>  
                    <label class="label">
                        <span class="legend">Diferença de arrecadação:</span>
                        <input name="tax_revenues" placeholder="Valor" />
                    </label>                         
                </div>

                <label class="label">
                    <span class="legend">*Observações:</span>
                    <textarea class="mce" name="observation"></textarea>
                </label>

                <div class="al-right">
                    <button class="btn btn-green icon-check-square-o">Criar Balanço</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <header class="dash_content_app_header">
            <h2 class="icon-bar-chart">Balanço nº <?= str_pad($invoiceCondominium->id, 3, 0, 0); ?></h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/controle/balanco/{$invoiceCondominium->id}"); ?>" method="post">
                <!--ACTION SPOOFING-->
                <input type="hidden" name="action" value="update"/>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Condomínio:</span>
                        <?php
                        $condominium = (new \Source\Models\WaterApp\AppCondominium())
                                ->find("id = {$invoiceCondominium->condominium_id}")
                                ->fetch();
                        ?>
                        <input value="<?= $condominium->name; ?>" disabled="disabled" style="cursor: not-allowed;">
                    </label>   
                    <label class="label">
                        <span class="legend">*Data de referência para o gráfico:</span>
                        <input type="text" class="mask-date" name="month_ref" placeholder="dd/mm/yyyy" 
                               value="<?= date_fmt($invoiceCondominium->month_ref, "d/m/Y"); ?>" required/>
                    </label>   
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Data de recebimento da conta:</span>
                        <input type="text" class="mask-date" name="due_at" placeholder="dd/mm/yyyy" 
                               value="<?= date_fmt($invoiceCondominium->due_at, "d/m/Y"); ?>" required/>
                    </label>     
                    <label class="label">
                        <span class="legend">*Data de criação do relatório:</span>
                        <input type="text" class="mask-date" name="report_at" placeholder="dd/mm/yyyy" 
                               value="<?= date_fmt($invoiceCondominium->report_at, "d/m/Y"); ?>" required/>
                    </label>                       
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Período da leitura da Exista:</span>
                        <input type="text" name="description" placeholder="Descrição" 
                               value="<?= $invoiceCondominium->description; ?>"/>
                    </label>       
                    <label class="label">
                        <span class="legend">*Período da leitura da Concessionária:</span>
                        <input type="text" name="description_dealership" placeholder="Descrição" 
                               value="<?= $invoiceCondominium->description_dealership; ?>" required/>
                    </label>  
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Consumo medido pela concessionária:</span>
                        <input name="consumption" placeholder="Valor" 
                               value="<?= $invoiceCondominium->consumption; ?>" required/>
                    </label> 
                    <label class="label">
                        <span class="legend">*Vencimento:</span>
                        <input type="text" class="mask-date" name="expiration" placeholder="dd/mm/yyyy" 
                               value="<?= date_fmt($invoiceCondominium->expiration, "d/m/Y"); ?>" required/>
                    </label>   
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Total da concessionária em R$:</span>
                        <input name="value" placeholder="Valor" 
                               value="<?= $invoiceCondominium->value; ?>" required/>
                    </label>   
                    <label class="label">
                        <span class="legend">*Valor por m<sup>3</sup>:</span>
                        <input name="value_per_m3" placeholder="Valor" 
                               value="<?= $invoiceCondominium->value_per_m3; ?>" required/>
                    </label>   
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Soma do consumo  medido pela Exista em m<sup>3</sup>:</span>
                        <input name="sum_consumption" placeholder="Valor" 
                               value="<?= $invoiceCondominium->sum_consumption; ?>" required/>
                    </label>   
                    <label class="label">
                        <span class="legend">*Valor a individualizar em R$ (por m<sup>3</sup>):</span>
                        <input name="individual_value" placeholder="Valor" 
                               value="<?= $invoiceCondominium->individual_value; ?>" required/>
                    </label>   
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Consumo da área comum em m<sup>3</sup>:</span>
                        <input name="reading_common_area" placeholder="Valor" 
                               value="<?= $invoiceCondominium->reading_common_area; ?>" required/>
                    </label>   
                    <label class="label">
                        <span class="legend">*Valor da área comum em R$:</span>
                        <input name="common_area" placeholder="Valor" 
                               value="<?= $invoiceCondominium->common_area; ?>" required/>
                    </label>   
                </div>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">Valor proporcional esgoto 50%  m<sup>3</sup>:</span>
                        <input name="sewer" placeholder="Valor" 
                               value="<?= $invoiceCondominium->sewer; ?>"/>
                    </label>   
                    <label class="label">
                        <span class="legend">Faixa de cobrança atingida:</span>
                        <input name="charge" placeholder="Valor" 
                               value="<?= $invoiceCondominium->charge; ?>" />
                    </label>                       
                </div>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">Previsão orçamentária</span>
                        <input name="budget" placeholder="Valor" 
                               value="<?= $invoiceCondominium->budget; ?>"/>
                    </label>   
                    <label class="label">
                        <span class="legend">Diferença de arrecadação:</span>
                        <input name="tax_revenues" placeholder="Valor" 
                               value="<?= $invoiceCondominium->tax_revenues; ?>" />
                    </label>                         
                </div>

                <label class="label">
                    <span class="legend">*Observações:</span>
                    <textarea class="mce" name="observation"><?= $invoiceCondominium->observation; ?></textarea>
                </label>

                <div class="app_form_footer">
                    <button class="btn btn-blue icon-check-square-o">Atualizar balanço</button>
                    <a href="#" class="remove_link icon-error"
                       data-post="<?= url("/admin/controle/balanco"); ?>"
                       data-action="delete"
                       data-confirm="Tem certeza que deseja excluir este balanço?"
                       data-subscription_id="<?= $invoiceCondominium->id; ?>">Excluir balanço</a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</section>