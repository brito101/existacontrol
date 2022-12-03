<?php $v->layout("_theme"); ?>

<!--FEATURED-->
<article class="home_featured">
    <div class="home_featured_content container content">
        <header class="home_featured_header">
            <h1>Buscando meios para diminuir sua conta de água?</h1>
            <p>Conheça o Sistema de medição individualizada de água <b>exista</b> e proporcione um consumo mais consciente e 
                justo em seu condomínio!<br><br>
            </p>
        </header>
        <div class="home_features">
            <section class="container content">

                <div class="home_features_content" style="margin-top: -60px; flex-basis: calc(25% - 20px);">
                    <article class="radius custom_feature">
                        <header>
                            <h3>Economia</h3>
                            <p>Redução média no consumo global dos edifícios na faixa de 30 a 40%, sua conta individual do tamanho 
                                do seu bolso.</p>
                        </header>
                    </article>

                    <article class="radius custom_feature">
                        <header>
                            <h3>Cobrança Justa</h3>
                            <p>Conheça e pague pelo seu consumo.</p>
                        </header>
                    </article>

                    <article class="radius custom_feature">
                        <header>
                            <h3>Controle transparente </h3>
                            <p>Identifique e evite desperdícios. Receba seus demonstrativos de consumo e tenha acesso on-line 
                                sempre que precisar.</p>
                        </header>
                    </article>

                    <article class="radius custom_feature">
                        <header>
                            <h3>Gerenciamento </h3>
                            <p>Receba relatórios que irão lhe auxiliar na gestão do seu condomínio de maneira simples e intuitiva, 
                                conte com automações poderosas para gerenciar tudo!</p>
                        </header>
                    </article>
                </div>
            </section>
        </div>

        <header class="home_featured_header" style="margin-top: -60px;">
            <p>
                O sistema de medição individual de água consiste na instalação de um hidrômetro para cada unidade habitacional ou 
                comercial, de modo que seja possível medir o seu consumo com a finalidade de emitir contas individuais.<br><br>
            </p>
        </header>

    </div>

    <div class="home_featured_app">
        <img src="<?= theme("/assets/images/home-app.jpg"); ?>" alt="existaControl" title="existaControl"/>
    </div>
</article>

<!--FEATURES-->
<div class="home_features">
    <section class="container content">
        <header class="home_features_header">
            <h2>O que você pode fazer com o existaControl?</h2>
            <p>Tenha acesso aos relatórios e demonstrativos on-line  de forma rápida, simples e segura. É tudo muito fácil veja:</p>
        </header>

        <div class="home_features_content">

            <article class="radius">
                <header>
                    <img alt="Cadastre-se como condômino ou Síndico." title="Cadastre-se como condômino ou Síndico."
                         src="<?= theme("/assets/images/home_receive.jpg"); ?>"/>
                    <p>Cadastre-se como condômino ou Síndico. </p>
                </header>
            </article>

            <article class="radius">
                <header>
                    <img alt="Acesse o existaControl, visualize seus demonstrativos e relatórios de forma simples, rápida e remota." title="Acesse o existaControl, visualize seus demonstrativos e relatórios de forma simples, rápida e remota. "
                         src="<?= theme("/assets/images/home_control.jpg"); ?>"/>
                    <p>Acesse o <b>existaControl</b>, visualize todas as informações sobre a medição do seu condomínio
                        (Síndico) ou sua unidade (condômino).</p>
                </header>
            </article>
        </div>

        <div class="new_features">
            <h2>Rápido | Simples | Seguro</h2>
        </div>

        <div>
            <article class="radius new_optin">
                <header class="gradient gradient-blue gradient-hover radius transition">
                    <h2>QUER CONHECER?</h2>
                    <p>Solicite uma visita de vistoria, teremos prazer em realizar seu orçamento.</p>
                </header>
            </article>
        </div>
    </section>
</div>

<!--OPTIN-->
<article class="home_optin">
    <div class="home_optin_content container content">
        <header class="home_optin_content_flex">
            <p>Envie-nos um e-mail com seus dados e os dados do condomínio que entraremos em contato
                para oferecer o melhor dos nossos serviços e garantir a sua comodidade na gestão de
                seu consumo e contas d'água.</p>
            <p>Pronto para começar a controlar?</p>
            <img alt="Entre em contato conosco" title="Entre em contato conosco"
                 src="<?= theme("/assets/images/optin-confirm-home.jpg"); ?>"/>

        </header>

        <div class="home_optin_content_flex">
            <span class="icon icon-envelope icon-notext"></span>
            <h4>Entre em contato conosco:</h4>
            <form action="<?= url("/contato"); ?>" method="post" enctype="multipart/form-data">
                <div class="ajax_response"><?= flash(); ?></div>
                <?= csrf_input(); ?>
                <input type="text" name="condominium" placeholder="Nome do Condomínio:" required/>
                <input type="text" name="address" placeholder="Endereço:" required/>
                <input type="text" name="city" placeholder="Cidade:" required/>
                <input type="text" name="manager" placeholder="Administradora:" required/>
                <input type="number" name="apartments" placeholder="Total de apartamentos:" required/>
                <input type="number" name="blocks" placeholder="Total de blocos:" required/>
                <input type="number" name="age" placeholder="Qual a idade do condomínio?" required/>

                <input list="metrial" name="metrial" placeholder="Material da tubulação:" required/>
                <datalist id="metrial">
                    <option value="PVC">
                    <option value="PPR">
                    <option value="Cobre">
                    <option value="Ferro">
                    <option value="Outros">
                </datalist>

                <input type="number" name="water_columns" placeholder="Quantidade de colunas de água por apartamento:" required/>                
                <input type="text" name="name_manager" placeholder="Nome do Síndico:" required/>
                <input type="text" name="requester" placeholder="Nome do solicitante:" required/>
                <input type="text" name="requester_function" placeholder="Função do solicitante:" required/>
                <input type="tel" name="phone" placeholder="Telefone:" required/>
                <input type="email" name="email" placeholder="Melhor e-mail:" required/>
                <button class="icon-paper-plane radius transition gradient gradient-blue gradient-hover">Enviar</button>
            </form>
        </div>
    </div>
</article>