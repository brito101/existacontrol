<?php
ob_start();

require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */
use CoffeeCode\Router\Router;
use Source\Core\Session;

$session = new Session();
$route = new Router(url(), ":");
$route->namespace("Source\App");

/**
 * WEB ROUTES
 */
$route->group(null);
$route->get("/", "Web:home");
$route->get("/sobre", "Web:about");

// services
$route->group("/servicos");
$route->get("/", "Web:service");
$route->get("/p/{page}", "Web:service");
$route->get("/{uri}", "Web:servicePost");
$route->post("/buscar", "Web:serviceSearch");
$route->get("/buscar/{search}/{page}", "Web:serviceSearch");
$route->get("/em/{category}", "Web:serviceCategory");
$route->get("/em/{category}/{page}", "Web:serviceCategory");

//auth
$route->group(null);
$route->get("/entrar", "Web:login");
$route->post("/entrar", "Web:login");
$route->get("/recuperar", "Web:forget");
$route->post("/recuperar", "Web:forget");
$route->get("/recuperar/{code}", "Web:reset");
$route->post("/recuperar/resetar", "Web:reset");

//optin
$route->group(null);
$route->get("/contato", "Web:optin");
$route->get("/obrigado", "Web:confirm");
$route->post("/contato", "Web:contact");

//services
$route->group(null);
$route->get("/termos", "Web:terms");

/**
 * APP
 */
$route->group("/app");
$route->get("/", "App:home");

$route->get("/historico", "App:income");
$route->get("/historico/p/{page}", "App:income");
$route->get("/historico/{date}", "App:income");
$route->get("/demonstrativo/{invoice}", "App:invoice");

$route->get("/historico-condominio", "App:incomeCondominium");
$route->get("/historico-condominio/p/{page}", "App:incomeCondominium");
$route->get("/historico-condominio/{date}", "App:incomeCondominium");
$route->get("/demonstrativo-condominio/{invoice}", "App:invoiceCondominium");

$route->get("/perfil", "App:profile");
$route->get("/sair", "App:logout");

$route->post("/dash", "App:dash");
$route->post("/support", "App:support");
$route->post("/profile", "App:profile");

/**
 * REGISTER ROUTES
 */
$route->group("/register");
$route->post("/registerCondominium", "Register:registerCondominium");
$route->post("/registerApartment", "Register:registerApartment");


/**
 * ADMIN ROUTES
 */
$route->namespace("Source\App\Admin");
$route->group("/admin");

//login
$route->get("/", "Login:root");
$route->get("/login", "Login:login");
$route->post("/login", "Login:login");

//dash
$route->get("/dash", "Dash:dash");
$route->get("/dash/home", "Dash:home");
$route->post("/dash/home", "Dash:home");
$route->get("/logoff", "Dash:logoff");

//control
$route->get("/controle/home", "Control:home");

$route->get("/controle/condominios", "Control:condominiums");
$route->get("/controle/condominios/{page}", "Control:condominiums");
$route->get("/controle/condominio", "Control:condominium");
$route->post("/controle/condominio", "Control:condominium");
$route->get("/controle/condominio/{condominium_id}", "Control:condominium");
$route->post("/controle/condominio/{condominium_id}", "Control:condominium");

$route->get("/controle/apartamentos", "Control:apartments");
$route->get("/controle/apartamentos/{page}", "Control:apartments");
$route->get("/controle/apartamento", "Control:apartment");
$route->post("/controle/apartamento", "Control:apartment");
$route->get("/controle/apartamento/{apartment_id}", "Control:apartment");
$route->post("/controle/apartamento/{apartment_id}", "Control:apartment");

$route->get("/controle/assinaturas", "Control:subscriptions");
$route->post("/controle/assinaturas", "Control:subscriptions");
$route->get("/controle/assinaturas/{search}/{page}", "Control:subscriptions");
$route->post("/controle/assinatura", "Control:subscription");
$route->get("/controle/assinatura", "Control:subscription");
$route->get("/controle/assinatura/{id}", "Control:subscription");
$route->post("/controle/assinatura/{id}", "Control:subscription");

$route->get("/controle/gerentes", "Control:subscriptionsCondominium");
$route->post("/controle/gerentes", "Control:subscriptionsCondominium");
$route->get("/controle/gerentes/{search}/{page}", "Control:subscriptionsCondominium");
$route->post("/controle/gerente", "Control:subscriptionCondominium");
$route->get("/controle/gerente", "Control:subscriptionCondominium");
$route->get("/controle/gerente/{id}", "Control:subscriptionCondominium");
$route->post("/controle/gerente/{id}", "Control:subscriptionCondominium");

$route->get("/controle/demonstrativos", "Control:invoices");
$route->post("/controle/demonstrativos", "Control:invoices");
$route->get("/controle/demonstrativos/{search}/{page}", "Control:invoices");
$route->get("/controle/demonstrativo/{invoice_id}", "Control:invoice");
$route->post("/controle/demonstrativo/{invoice_id}", "Control:invoice");

$route->get("/controle/balancos", "Control:invoicesCondominium");
$route->post("/controle/balancos", "Control:invoicesCondominium");
$route->get("/controle/balancos/{search}/{page}", "Control:invoicesCondominium");
$route->get("/controle/balanco", "Control:invoiceCondominium");
$route->post("/controle/balanco", "Control:invoiceCondominium");
$route->get("/controle/balanco/{id}", "Control:invoiceCondominium");
$route->post("/controle/balanco/{id}", "Control:invoiceCondominium");

$route->get("/controle/importar", "Control:import");
$route->post("/controle/importar", "Control:import");

$route->get("/controle/fotos", "Control:photos");
$route->post("/controle/fotos", "Control:photos");

//services
$route->get("/servicos/home", "Blog:home");
$route->post("/servicos/home", "Blog:home");
$route->get("/servicos/home/{search}/{page}", "Blog:home");
$route->get("/servicos/post", "Blog:post");
$route->post("/servicos/post", "Blog:post");
$route->get("/servicos/post/{post_id}", "Blog:post");
$route->post("/servicos/post/{post_id}", "Blog:post");
$route->get("/servicos/categorias", "Blog:categories");
$route->get("/servicos/categorias/{page}", "Blog:categories");
$route->get("/servicos/categoria", "Blog:category");
$route->post("/servicos/categoria", "Blog:category");
$route->get("/servicos/categoria/{category_id}", "Blog:category");
$route->post("/servicos/categoria/{category_id}", "Blog:category");

//faqs
$route->get("/faq/home", "Faq:home");
$route->get("/faq/home/{page}", "Faq:home");
$route->get("/faq/canal", "Faq:channel");
$route->post("/faq/canal", "Faq:channel");
$route->get("/faq/canal/{channel_id}", "Faq:channel");
$route->post("/faq/canal/{channel_id}", "Faq:channel");
$route->get("/faq/pergunta/{channel_id}", "Faq:question");
$route->post("/faq/pergunta/{channel_id}", "Faq:question");
$route->get("/faq/pergunta/{channel_id}/{question_id}", "Faq:question");
$route->post("/faq/pergunta/{channel_id}/{question_id}", "Faq:question");

//users
$route->get("/usuarios/home", "Users:home");
$route->post("/usuarios/home", "Users:home");
$route->get("/usuarios/home/{search}/{page}", "Users:home");
$route->get("/usuarios/usuario", "Users:user");
$route->post("/usuarios/usuario", "Users:user");
$route->get("/usuarios/usuario/{user_id}", "Users:user");
$route->post("/usuarios/usuario/{user_id}", "Users:user");

//END ADMIN
$route->namespace("Source\App");

/**
 * ERROR ROUTES
 */
$route->group("/ops");
$route->get("/{errcode}", "Web:error");

/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();
