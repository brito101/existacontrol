<div class="dash_content_sidebar">
    <h3 class="icon-asterisk">Dashboard/Usuários</h3>
    <p class="dash_content_sidebar_desc">Gerencie, monitore e acompanhe os usuários do seu site aqui...</p>

    <nav>
        <?php
        $nav = function ($icon, $href, $title) use ($app) {
            $active = ($app == $href ? "active" : null);
            $url = url("/admin/{$href}");
            return "<a class=\"icon-{$icon} radius {$active}\" href=\"{$url}\">{$title}</a>";
        };

        echo $nav("user", "usuarios/home", "Usuários");
        echo $nav("plus-circle", "usuarios/usuario", "Novo usuário");
        ?>

        <?php if (!empty($user) && $user->photo()): ?>
            <img class="radius" style="width: 100%; margin-top: 30px" src="<?= image($user->photo, 600, 600); ?>"/>
        <?php endif; ?>
    </nav>
</div>