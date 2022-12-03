<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/control/sidebar.php"); ?>

<section class="dash_content_app">
    <?php if (!$subscription): ?>
        <header class="dash_content_app_header">
            <h2 class="icon-briefcase">Novo Gerente</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/controle/gerente"); ?>" method="post">
                <!--ACTION SPOOFING-->
                <input type="hidden" name="action" value="create"/>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Usuário:</span>
                        <?php if (!empty($candidate_users)): ?>
                            <select name="user_id" required>
                                <?php
                                foreach ($candidate_users as $user):
                                    $user_id = $user->id;
                                    $selected = function ($value) use ($user_id) {
                                        return ($user_id == $value ? "selected" : "");
                                    };
                                    ?>
                                    <option <?= $selected($user->id); ?> value="<?= $user->id; ?>">
                                        <?= $user->fullName(); ?>
                                    <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <select name="user_id">
                                <option value="null">
                                    Não há usuário com perfil de gerente</select>
                        <?php endif; ?>
                    </label>     
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
                                    <?= str_limit_words($apartment->name, 5); ?>
                                <?php endforeach; ?>
                        </select>
                    </label>     
                </div>
                <div class="al-right">
                    <button class="btn btn-green icon-check-square-o">Criar Gerente</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <header class="dash_content_app_header">
            <h2 class="icon-briefcase">Gerente nº <?= str_pad($subscription->id, 3, 0, 0); ?> - <?= $subscription->user()->fullName(); ?></h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/controle/gerente/{$subscription->id}"); ?>" method="post">
                <!--ACTION SPOOFING-->
                <input type="hidden" name="action" value="update"/>

                <div class="label_g2">

                    <label class="label">
                        <span class="legend">*Usuário:</span>
                        <select name="user_id" required>
                            <option value="<?= $subscription->user_id; ?>" selected> <?= $subscription->user()->fullName(); ?></option>
                            <?php
                            if (!empty($candidate_users)):
                                foreach ($candidate_users as $user):
                                    $user_id = $user->id;
                                    $selected = function ($value) use ($user_id) {
                                        return ($user_id == $value);
                                    };
                                    ?>
                                    <option <?= $selected($user->id); ?> value="<?= $user->id; ?>">
                                        <?= $user->fullName(); ?>
                                    <?php
                                    endforeach;
                                endif;
                                ?>
                        </select>
                    </label>     

                    <label class="label">
                        <span class="legend">*Condomínio:</span>
                        <select name="condominium_id" required>
                            <option value="<?= $subscription->condominium_id; ?>" selected>
                                <?=
                                $condominium = (new \Source\Models\WaterApp\AppCondominium())
                                        ->find("id = {$subscription->condominium_id }")
                                        ->fetch()->name;
                                ?>

                                <?php
                                foreach ($condominiuns as $apartment):
                                    $apartment_id = $subscription->id;
                                    $selected = function ($value) use ($apartment_id) {
                                        return ($apartment_id == $value);
                                    };
                                    ?>
                                <option <?= $selected($apartment->id); ?> value="<?= $apartment->id; ?>">
                                    <?= str_limit_words($apartment->name, 5); ?>
    <?php endforeach; ?>
                        </select>
                    </label>     
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Status do Gerente:</span>
                        <select name="status" required>
                            <?php
                            $status = $subscription->status;
                            $selected = function ($value) use ($status) {
                                return ($status == $value ? "selected" : "");
                            };
                            ?>
                            <option <?= $selected("active"); ?> value="active">Ativo</option>
                            <option <?= $selected("canceled"); ?> value="canceled">Cancelado
                            </option>
                        </select>
                    </label>
                </div>
                <div class="app_form_footer">
                    <button class="btn btn-blue icon-check-square-o">Atualizar gerente</button>
                    <a href="#" class="remove_link icon-error"
                       data-post="<?= url("/admin/controle/gerente"); ?>"
                       data-action="delete"
                       data-confirm="Tem certeza que deseja excluir este gerente?"
                       data-subscription_id="<?= $subscription->id; ?>">Excluir Gerente</a>
                </div>
            </form>
        </div>
<?php endif; ?>
</section>