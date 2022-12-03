<div class="app_sidebar_nav">
    <a class="icon-bar-chart radius transition" title="Dashboard" href="<?= url("/app"); ?>">Controle</a>
    <?php if ($user->level == 2): ?>
        <a class="icon-calendar radius transition" title="Histórico de moradores" href="<?= url("/app/historico"); ?>">Hist. Moradores</a>
        <a class="icon-calendar-check-o radius transition" title="Histórico do Condomínio" href="<?= url("/app/historico-condominio"); ?>">Hist. Condomínio</a>
    <?php else: ?>
        <a class="icon-calendar-check-o radius transition" title="Histórico" href="<?= url("/app/historico"); ?>">Histórico</a>
    <?php endif; ?>
    <a class="icon-user radius transition" title="Perfil" href="<?= url("/app/perfil"); ?>">Perfil</a>
    <span class="icon-life-ring radius transition" title="Suporte" data-modalopen=".app_modal_contact">Suporte</span>
    <a class="icon-sign-out radius transition" title="Sair" href="<?= url("/app/sair"); ?>">Sair</a>
</div>