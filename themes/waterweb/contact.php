<?php $v->layout("_theme"); ?>

<article class="about_optin">
    <div class="home_optin_content container content">
        <header class="home_optin_content_flex">
            <h2>O existaControl é um gerenciador de medições e relatórios de consumo de água</h2>
            <p>Com ele você consulta suas contas de água e conta com automações e relatórios poderosos que
                controlam tudo!</p>
            <p>Enive-nos um e-mail com seus dados e os dados do condomínio que entraremos em contato
                para oferecer o melhor dos nossos serviços e garantir a sua comodidade na gestão de
                seu consumo e contas d'água.</p>
            <p>Pronto para começar a controlar?</p>
        <img alt="Entre em contato conosco" title="Entre em contato conosco"
                 src="<?= theme("/assets/images/optin-confirm.jpg"); ?>"/>

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

                <input list="metrial" name="metrial" placeholder="Metrial de tubulação:" required/>
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