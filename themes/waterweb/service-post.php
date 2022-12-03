<?php $v->layout("_theme"); ?>

<article class="post_page">
    <header class="post_page_header">
        <div class="post_page_hero">
            <h1><?= $service->title; ?></h1>
            <img class="post_page_cover" alt="<?= $service->title; ?>" title="<?= $service->title; ?>"
                 src="<?= image($service->cover, 1280); ?>"/>
            <div class="post_page_meta">
                <div class="author">
                    <div><img alt="<?= "{$service->author()->first_name} {$service->author()->last_name}"; ?>"
                              title="<?= "{$service->author()->first_name} {$service->author()->last_name}"; ?>"
                              src="<?= image($service->author()->photo, 200, 200); ?>"/></div>
                    <div class="name">
                        Por: <?= "{$service->author()->first_name} {$service->author()->last_name}"; ?>
                    </div>
                </div>
                <div class="date">Dia <?= date_fmt($service->post_at); ?></div>
            </div>
        </div>
    </header>

    <div class="post_page_content">
        <div class="htmlchars">
            <h2><?= $service->subtitle; ?></h2>
            <?= html_entity_decode($service->content); ?>
        </div>
    </div>

    <?php if (!empty($related)): ?>
        <div class="post_page_related content">
            <section>
                <header class="post_page_related_header">
                    <h4>Veja também:</h4>
                    <p>Confira mais serviços relacionados e veja como trabalhamos para melhorar o de suas contas de água.</p>
                </header>

                <div class="blog_articles">
                    <?php foreach ($related as $more): ?>
                        <?php $v->insert("service-list", ["service" => $more]); ?>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    <?php endif; ?>
</article>