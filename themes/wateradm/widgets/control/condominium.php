<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/control/sidebar.php"); ?>

<section class="dash_content_app">
    <?php if (!$condominium): ?>
        <header class="dash_content_app_header">
            <h2 class="icon-map-marker">Novo Condomínio</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/controle/condominio"); ?>" method="post">
                <!--ACTION SPOOFING-->
                <input type="hidden" name="action" value="create"/>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Condomínio:</span>
                        <input type="text" name="name" placeholder="Nome do condomínio" required/>
                    </label>

                    <label class="label">
                        <span class="legend">*Endereço:</span>
                        <input type="text" name="address" placeholder="Endereço do condomínio" required/>
                    </label>
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Concessionária:</span>
                        <input type="text" name="dealership" placeholder="Concessionária de água" required/>
                    </label>

                    <label class="label">
                        <span class="legend">*Status:</span>
                        <select name="status" required>
                            <option value="active">Ativo</option>
                            <option value="inactive">Inativo</option>
                        </select>
                    </label>
                </div>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Apelido:</span>
                        <input type="text" name="alias" placeholder="Apelido para upload de imagens" required/>
                    </label>
                </div>

                <div class="al-right">
                    <button class="btn btn-green icon-check-square-o">Criar Condomínio</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <header class="dash_content_app_header">
            <h2 class="icon-pencil-square-o">Editar Condomínio</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/controle/condominio/{$condominium->id}"); ?>" method="post">
                <!--ACTION SPOOFING-->
                <input type="hidden" name="action" value="update"/>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Condomínio:</span>
                        <input type="text" name="name" value="<?= $condominium->name; ?>" required/>
                    </label>

                    <label class="label">
                        <span class="legend">*Endereço:</span>
                        <input type="text" name="address" value="<?= $condominium->address; ?>" required/>
                    </label>
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Concessionária:</span>
                        <input type="text" name="dealership"  value="<?= $condominium->dealership; ?>" required/>
                    </label>
                    <label class="label">
                        <span class="legend">*Status:</span>
                        <select name="status" required>
                            <?php
                            $status = $condominium->status;
                            $selected = function ($value) use ($status) {
                                return ($status == $value ? "selected" : "");
                            };
                            ?>
                            <option <?= $selected("active"); ?> value="active">Ativo</option>
                            <option <?= $selected("inactive"); ?> value="inactive">Inativo</option>
                        </select>
                    </label>
                </div>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Apelido:</span>
                        <input type="text" name="alias"  value="<?= $condominium->alias; ?>" required/>
                    </label>
                </div>

                <div class="app_form_footer">
                    <button class="btn btn-blue icon-check-square-o">Atualizar</button>
                    <?php if (!$subscribers): ?>
                        <a href="#" class="remove_link icon-error"
                           data-post="<?= url("/admin/controle/condominio"); ?>"
                           data-action="delete"
                           data-confirm="Tem certeza que deseja excluir este condomínio?"
                           data-condominium_id="<?= $condominium->id; ?>">Excluir Condomínio</a>
                       <?php endif; ?>
                </div>
            </form>
        </div>
    <?php endif; ?>
</section>
