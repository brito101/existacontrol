<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/control/sidebar.php"); ?>

<section class="dash_content_app">
    <?php if (!$subscription): ?>
        <header class="dash_content_app_header">
            <h2 class="icon-pencil-square-o">Nova Assinatura</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/controle/assinatura"); ?>" method="post">
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
                                    Não há usuários sem assinatura</select>
                        <?php endif; ?>
                    </label>     
                    <label class="label">
                        <span class="legend">*Apartamento:</span>
                        <select name="apartment_id" required>
                            <?php
                            foreach ($apartments as $apartment):
                                $apartment_id = $apartment->id;
                                $selected = function ($value) use ($apartment_id) {
                                    return ($apartment_id == $value ? "selected" : "");
                                };
                                ?>
                                <option <?= $selected($apartment->id); ?> value="<?= $apartment->id; ?>">
                                    <?= $apartment->block; ?> - <?= $apartment->number; ?> (<?= $apartment->condominiumName()->name; ?>)
                                <?php endforeach; ?>
                        </select>
                    </label>     
                </div>
                <div class="al-right">
                    <button class="btn btn-green icon-check-square-o">Criar Assinatura</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <header class="dash_content_app_header">
            <h2>Assinante nº <?= str_pad($subscription->id, 3, 0, 0); ?> - <?= $subscription->user()->fullName(); ?></h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/controle/assinatura/{$subscription->id}"); ?>" method="post">
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
                                    <?php endforeach;
                                endif;
                                ?>
                        </select>
                    </label>     

                    <label class="label">
                        <span class="legend">*Apartamento:</span>
                        <select name="apartment_id" required>
                            <?php
                            foreach ($apartments as $apartment):
                                $apartment_id = $subscription->apartment()->id;
                                $selected = function ($value) use ($apartment_id) {
                                    return ($apartment_id == $value ? "selected" : "");
                                };
                                ?>
                                <option <?= $selected($apartment->id); ?> value="<?= $apartment->id; ?>">
                                    <?= $apartment->block; ?> - <?= $apartment->number; ?> (<?= $apartment->condominiumName()->name; ?>)
    <?php endforeach; ?>
                        </select>
                    </label>     
                </div>
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Status da assinatura:</span>
                        <select name="status" required>
                            <?php
                            $status = $subscription->status;
                            $selected = function ($value) use ($status) {
                                return ($status == $value ? "selected" : "");
                            };
                            ?>
                            <option <?= $selected("active"); ?> value="active">Ativa</option>
                            <option <?= $selected("canceled"); ?> value="canceled">Cancelada
                            </option>
                        </select>
                    </label>
                </div>
                <div class="app_form_footer">
                    <button class="btn btn-blue icon-check-square-o">Atualizar assinatura</button>
                    <a href="#" class="remove_link icon-error"
                       data-post="<?= url("/admin/controle/assinatura"); ?>"
                       data-action="delete"
                       data-confirm="Tem certeza que deseja excluir esta assinatura?"
                       data-subscription_id="<?= $subscription->id; ?>">Excluir Assinatura</a>
                </div>
            </form>
        </div>
<?php endif; ?>
</section>