<div class="dash_content_sidebar">
    <h3 class="icon-asterisk">Dashboard/Controle</h3>
    <p class="dash_content_sidebar_desc">Condomínios, assinantes e gestão do <?= CONF_SITE_NAME; ?>? Está tudo aqui...</p>

    <nav>
        <?php
        $nav = function ($icon, $href, $title) use ($app) {
            $active = ($app == $href ? "active" : null);
            $url = url("/admin/{$href}");
            return "<a class=\"icon-{$icon} radius {$active}\" href=\"{$url}\">{$title}</a>";
        };

        echo $nav("bar-chart", "controle/home", "Controle");
        echo $nav("map-marker", "controle/condominios", "Condomínios");
        echo $nav("briefcase", "controle/gerentes", "Gerentes");
        echo $nav("home", "controle/apartamentos", "Apartamentos");
        echo $nav("pencil-square-o", "controle/assinaturas", "Assinaturas");
        echo $nav("bar-chart", "controle/balancos", "Balanços");
        echo $nav("folder-o", "controle/demonstrativos", "Demonstrativos");
        ?>
    </nav>
</div>