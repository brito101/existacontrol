<article class="blog_article">
    <a title="<?= $service->title; ?>" href="<?= url("/servicos/{$service->uri}"); ?>">
        <img title="<?= $service->title; ?>" alt="<?= $service->title; ?>" src="<?= image($service->cover, 600, 340); ?>"/>
    </a>
    <header>
        <p class="meta">
            <a title="serviços em <?= $service->category()->title; ?>"
               href="<?= url("/servicos/em/{$service->category()->uri}"); ?>"><?= $service->category()->title; ?></a>
            &bull; Por <?= "{$service->author()->first_name} {$service->author()->last_name}"; ?>
            &bull; <?= date_fmt($service->post_at); ?>
        </p>
        <h2><a title="<?= $service->title; ?>" href="<?= url("/servicos/{$service->uri}"); ?>"><?= $service->title; ?></a></h2>
        <p><a title="<?= $service->title; ?>" href="<?= url("/servicos/{$service->uri}"); ?>">
                <?= str_limit_chars($service->subtitle, 120); ?></a></p>
    </header>
</article>