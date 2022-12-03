<?php $v->layout("_login"); ?>
<div class="login">
    <article class="login_box radius">
        <div class="ajax_response"><?= flash(); ?></div>
        <form name="login" action="<?= url("/admin/login"); ?>" method="post">
            <header>
                <h1 class="icon-cog transition" style="text-align: center; margin-top: -20px; padding-bottom: 20px;">exista<b>Admin</b></h1>
            </header>
            <label>
                <span class="field icon-envelope">E-mail:</span>
                <input name="email" type="email" placeholder="Informe seu e-mail" required/>
            </label>
            <label>
                <span class="field icon-unlock-alt">Senha:</span>
                <input name="password" type="password" placeholder="Informe sua senha:" required/>
            </label>
            <button class="radius gradient gradient-blue gradient-hover icon-sign-in">Entrar</button>
        </form>
        <footer>
            <span>                   
                <img src="<?= theme("/assets/images/favicon-e.png"); ?>" width="20px" style="margin: 0 -5px -8px 0;"/>
                xistaControl - Todos os direitos reservados  &copy
            </span>
        </footer>
    </article>
</div>