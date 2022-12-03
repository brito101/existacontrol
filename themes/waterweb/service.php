<?php $v->layout("_theme_service"); ?>

<section class="blog_page">
    <header class="blog_page_header">
        <h1><?= ($title ?? "SERVIÇOS"); ?></h1>
        <p><?= ($search ?? $desc ?? "Confira nossos serviços que ajudam você a controlar melhor seus demonstrativos de consumo de água"); ?></p>
        <form name="search" action="<?= url("/servicos/buscar"); ?>" method="post" enctype="multipart/form-data">
            <label>
                <input type="text" name="s" placeholder="Encontre um serviço:" required/>
                <button class="icon-search icon-notext" style="background: #FFF"></button>
            </label>
        </form>
    </header>

    <?php if (empty($services) && !empty($search)): ?>
        <div class="content content">
            <div class="empty_content">
                <h3 class="empty_content_title">Sua pesquisa não retornou resultados :/</h3>
                <p class="empty_content_desc">Você pesquisou por <b><?= $search; ?></b>. Tente outros termos.</p>
                <a class="empty_content_btn gradient gradient-blue gradient-hover radius"
                   href="<?= url("/servicos"); ?>" title="Serviços">...ou volte aos serviços</a>
            </div>
        </div>
    <?php elseif (empty($services)): ?>
        <div class="content content">
            <div class="empty_content">
                <h3 class="empty_content_title">Ainda estamos trabalhando aqui!</h3>
                <p class="empty_content_desc">Estamos preparando um serviço de primeira para você :)</p>
            </div>
        </div>
    <?php else: ?>
        <div class="blog_content container content">
            <div class="blog_articles">
                <?php foreach ($services as $service): ?>
                    <?php $v->insert("service-list", ["service" => $service]); ?>
                <?php endforeach; ?>
            </div>

            <?= $paginator; ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($faq)): ?>
    <section class="faq">
        <div class="faq_content content container">
            <header class="faq_header">
                <img class="title_image" title="Perguntas frequentes" alt="Perguntas frequentes"
                     src="<?= theme("/assets/images/faq-title.jpg"); ?>"/>
                <h3>Perguntas frequentes:</h3>
                <p>Confira as principais dúvidas e repostas sobre o existaControl.</p>
            </header>
            <div class="faq_asks">
                <?php foreach ($faq as $question): ?>
                    <article class="faq_ask j_collapse">
                        <h4 class="j_collapse_icon icon-plus"><?= $question->question; ?></h4>
                        <div class="faq_ask_coll j_collapse_box"><?= $question->response; ?></div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
    
</section>

