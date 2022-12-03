<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/control/sidebar.php"); ?>

<section class="dash_content_app">
    <?php if (!$apartment): ?>
        <header class="dash_content_app_header">
            <h2 class="icon-home">Novo Apartamento</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/controle/apartamento"); ?>" method="post">
                <!--ACTION SPOOFING-->
                <input type="hidden" name="action" value="create"/>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Bloco:</span>
                        <input type="text" name="block" placeholder="Bloco" required/>
                    </label>

                    <label class="label">
                        <span class="legend">*Apartamento:</span>
                        <input type="text" name="number" placeholder="Apartamento" required/>
                    </label>
                </div>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Hidrômetro:</span>
                        <input type="text" name="hydrometer" placeholder="Hidrômetro" required/>
                    </label>

                    <label class="label">
                        <span class="legend">*Localização:</span>
                        <input type="text" name="location" placeholder="Localização" required/>
                    </label>
                </div>

                <span class="legend">*Condomínio:</span>
                <select name="condominium_id" required>
                    <?php
                    foreach ($condominiuns as $condominium):
                        $condominium_id = $condominium->id;
                        $selected = function ($value) use ($condominium_id) {
                            return ($condominium_id == $value ? "selected" : "");
                        };
                        ?>
                        <option <?= $selected($condominium->id); ?> value="<?= $condominium->id; ?>"><?= $condominium->name; ?>
                        <?php endforeach; ?>
                </select>

                <label class="label">
                    <span class="legend">*Status:</span>
                    <select name="status" required>
                        <option value="active">Ativo</option>
                        <option value="inactive">Inativo</option>
                    </select>
                </label>

                <div class="al-right">
                    <button class="btn btn-green icon-check-square-o">Criar Apartamento</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <header class="dash_content_app_header">
            <h2 class="icon-pencil-square-o">Editar Apartamento</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/controle/apartamento/{$apartment->id}"); ?>" method="post">
                <!--ACTION SPOOFING-->
                <input type="hidden" name="action" value="update"/>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Bloco:</span>
                        <input type="text" name="block" value="<?= $apartment->block; ?>" required/>
                    </label>

                    <label class="label">
                        <span class="legend">*Apartamento:</span>
                        <input type="text" name="number" value="<?= $apartment->number; ?>" required/>
                    </label>
                </div>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Hidrômetro:</span>
                        <input type="text" name="hydrometer" value="<?= $apartment->hydrometer; ?>" required/>
                    </label>

                    <label class="label">
                        <span class="legend">*Localização:</span>
                        <input type="text" name="location" value="<?= $apartment->location; ?>" required/>
                    </label>
                </div>

                <span class="label">*Condomínio:</span>
                <select name="condominium_id" required>
                    <?php
                    foreach ($condominiuns as $condominium):
                        $condominium_id = $condominium->id;
                        $selected = function ($value) use ($condominium_id) {
                            return ($condominium_id == $value ? "selected" : "");
                        };
                        ?>
                        <option <?= $selected($condominium->id); ?> value="<?= $condominium->id; ?>">
                        <?= $condominium->name; ?></option>
                        <?php endforeach; ?>
                </select>


                <label class="label">
                    <span class="legend">*Status:</span>
                    <select name="status" required>
                        <?php
                        $status = $apartment->status;
                        $selected = function ($value) use ($status) {
                            return ($status == $value ? "selected" : "");
                        };
                        ?>
                        <option <?= $selected("active"); ?> value="active">Ativo</option>
                        <option <?= $selected("inactive"); ?> value="inactive">Inativo</option>
                    </select>
                </label>

                <div class="app_form_footer">
                    <button class="btn btn-blue icon-check-square-o">Atualizar</button>
                    <?php if (!$subscribers): ?>
                        <a href="#" class="remove_link icon-error"
                           data-post="<?= url("/admin/controle/apartamento"); ?>"
                           data-action="delete"
                           data-confirm="Tem certeza que deseja excluir este apartamento?"
                           data-apartment_id="<?= $apartment->id; ?>">Excluir Apartamento</a>
                       <?php endif; ?>
                </div>
            </form>
        </div>
    <?php endif; ?>
</section>
