<div class="dash_content_sidebar">
    <h3 class="icon-asterisk">Dashboard/Serviços</h3>
    <p class="dash_content_sidebar_desc">Aqui você gerencia todos os artigos e categorias de serviços...</p>

    <nav>
        <?php
        $nav = function ($icon, $href, $title) use ($app) {
            $active = ($app == $href ? "active" : null);
            $url = url("/admin/{$href}");
            return "<a class=\"icon-{$icon} radius {$active}\" href=\"{$url}\">{$title}</a>";
        };

        echo $nav("pencil-square-o", "servicos/home", "Serviços");
        echo $nav("bookmark", "servicos/categorias", "Categorias");
        echo $nav("plus-circle", "servicos/post", "Novo Serviço");
        ?>

        <?php if (!empty($post->cover)): ?>
            <img class="radius" style="width: 100%; margin-top: 30px" src="<?= image($post->cover, 680); ?>"/>
        <?php endif; ?>

        <?php if (!empty($category->cover)): ?>
            <img class="radius" style="width: 100%; margin-top: 30px" src="<?= image($category->cover, 680); ?>"/>
        <?php endif; ?>
    </nav>
</div>