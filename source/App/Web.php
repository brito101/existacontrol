<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\Faq\Question;
use Source\Models\Post;
use Source\Models\User;
use Source\Support\Pager;
use Source\Models\Category;
use Source\Models\Report\Access;
use Source\Models\Report\Online;
use Source\Support\Email;
use Source\Core\View;

class Web extends Controller {

    /**
     * Web constructor.
     */
    public function __construct() {

//        redirect("/ops/manutencao");

        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");

        (new Access())->report();
        (new Online())->report();
    }

    /**
     * SITE HOME
     */
    public function home(): void {
        $head = $this->seo->render(
                CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
                CONF_SITE_DESC,
                url(),
                theme("/assets/images/share.jpg")
        );
        echo $this->view->render("home", [
            "head" => $head,
            "video" => "a34o8no3LIQ",
            "services" => (new Post())
                    ->findPost()
                    ->order("post_at DESC")
                    ->limit(6)
                    ->fetch(true)
        ]);
    }

    /**
     * SITE ABOUT
     */
    public function about(): void {
        $head = $this->seo->render(
                "Descubra o " . CONF_SITE_NAME . " - " . CONF_SITE_DESC,
                CONF_SITE_DESC,
                url("/sobre"),
                theme("/assets/images/share.jpg")
        );

        $faq = (new Question())
                ->find()
                ->order("channel_id, order_by")
                ->fetch(true);
        echo $this->view->render("about", [
            "head" => $head,
//            "video" => "a34o8no3LIQ",
            "faq" => $faq
        ]);
    }

    /**
     * SITE SERVICES
     * @param array|null $data
     */
    public function service(?array $data): void {
        $head = $this->seo->render(
                "Serviços - " . CONF_SITE_NAME,
                "Confira em nossos serviços dicas e sacadas de como controlar melhor suas contas de água.",
                url("/servicos"),
                theme("/assets/images/share.jpg")
        );

        $faq = (new Question())
                ->find()
                ->order("channel_id, order_by")
                ->fetch(true);

        $services = (new Post())->findPost();
        $pager = new Pager(url("/servicos/p/"));
        $pager->pager($services->count(), 9, ($data['page'] ?? 1));
        echo $this->view->render("service", [
            "head" => $head,
            "services" => $services->order('post_at DESC')
                    ->limit($pager->limit())
                    ->offset($pager->offset())
                    ->fetch(true),
            "paginator" => $pager->render(),
            "faq" => $faq
        ]);
    }

    /**
     * SITE SERVICE CATEGORY
     * @param array $Data
     */
    public function serviceCategory(array $data): void {
        $categoryUri = filter_var($data["category"], FILTER_SANITIZE_STRIPPED);
        $category = (new Category())->findByUri($categoryUri);

        if (!$category) {
            redirect("/servicos");
        }

        $serviceCategory = (new Post())->findPost("category = :c", "c={$category->id}");
        $page = (!empty($data['page']) && filter_var($data['page'], FILTER_VALIDATE_INT) >= 1 ? $data['page'] : 1);
        $pager = new Pager(url("/servicos/em/{$category->uri}/"));
        $pager->pager($serviceCategory->count(), 9, $page);

        $head = $this->seo->render(
                "Serviços em {$category->title} - " . CONF_SITE_NAME,
                $category->description,
                url("/servicos/em/{$category->uri}/{$page}"),
                ($category->cover ? image($category->cover, 1200, 628) : theme("/assets/images/share.jpg"))
        );

        echo $this->view->render("service", [
            "head" => $head,
            "title" => "Serviços em {$category->title}",
            "desc" => $category->description,
            "services" => $serviceCategory
                    ->limit($pager->limit())
                    ->offset($pager->offset())
                    ->order("post_at DESC")
                    ->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * SITE SERVICE SEARCH
     * @param array $data
     * @return void
     */
    public function serviceSearch(array $data): void {
        if (!empty($data['s'])) {
            $search = str_search($data["s"]);
            echo json_encode(["redirect" => url("/servicos/buscar/{$search}/1")]);
            return;
        }

        $search = str_search($data["search"]);
        $page = (filter_var($data['page'], FILTER_VALIDATE_INT) >= 1 ? $data['page'] : 1);

        if ($search == "all") {
            redirect("/servicos");
        }

        $head = $this->seo->render(
                "Pesquisa por {$search} - " . CONF_SITE_NAME,
                "Confira os resultados de sua pesquisa para {$search}",
                url("/servicos/buscar/{$search}/{$page}"),
                theme("/assets/images/share.jpg")
        );

        $serviceSearch = (new Post())->findPost("MATCH(title, subtitle) AGAINST(:s)", "s={$search}");

        if (!$serviceSearch->count()) {
            echo $this->view->render("service", [
                "head" => $head,
                "title" => "PESQUISA POR:",
                "search" => $search
            ]);
            return;
        }

        $pager = new Pager(url("/servicos/buscar/{$search}/"));
        $pager->pager($serviceSearch->count(), 9, $page);
        echo $this->view->render("service", [
            "head" => $head,
            "title" => "PESQUISA POR:",
            "search" => $search,
            "services" => $serviceSearch->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * SITE SERVICE POST
     * @param array $data
     */
    public function servicePost(?array $data): void {
        $service = (new Post())->findByUri($data["uri"]);
        if (!$service) {
            redirect("/404");
        }

        $user = Auth::user();
        if (!$user || $user->level < 5) {
            $service->views += 1;
            $service->save();
        }

        $head = $this->seo->render(
                "{$service->title} - " . CONF_SITE_NAME,
                $service->subtitle,
                url("/servicos/{$service->uri}"),
                ($service->cover ? image($service->cover, 1200, 628) : theme("/assets/images/share.jpg"))
        );
        echo $this->view->render("service-post", [
            "head" => $head,
            "service" => $service,
            "related" => (new Post())
                    ->findPost("category = :c AND id != :i", "c={$service->category}&i={$service->id}")
                    ->order("rand()")
                    ->limit(3)
                    ->fetch(true)
        ]);
    }

    /**
     * SITE LOGIN
     * @param null|array $data
     */
    public function login(?array $data): void {
        if (Auth::user()) {
            redirect("/app");
        }

        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (request_limit("weblogin", 3, 60 * 5)) {
                $json['message'] = $this->message->error("Você já efetuou 3 tentativas, esse é o limite. Por favor, aguarde 5 minutos para tentar novamente!")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data['email']) || empty($data['password'])) {
                $json['message'] = $this->message->warning("Informe seu email e senha para entrar")->render();
                echo json_encode($json);
                return;
            }

            $save = (!empty($data['save']) ? true : false);
            $auth = new Auth();
            $login = $auth->login($data['email'], $data['password'], $save);

            if ($login) {
                $this->message->success("Seja bem-vindo(a) de volta " . Auth::user()->first_name . "!")->flash();
                $json['redirect'] = url("/app");
            } else {
                $json['message'] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
                "Entrar - " . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/entrar"),
                theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-login", [
            "head" => $head,
            "cookie" => filter_input(INPUT_COOKIE, "authEmail")
        ]);
    }

    /**
     * SITE PASSWORD FORGET
     * @param null|array $data
     */
    public function forget(?array $data) {
        if (Auth::user()) {
            redirect("/app");
        }
        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data["email"])) {
                $json['message'] = $this->message->info("Informe seu e-mail para continuar")->render();
                echo json_encode($json);
                return;
            }

            if (request_repeat("webforget", $data["email"])) {
                $json['message'] = $this->message->error("Ooops! Você já tentou este e-mail antes")->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();
            if ($auth->forget($data["email"])) {
                $json["message"] = $this->message->success("Acesse seu e-mail para recuperar a senha")->render();
            } else {
                $json["message"] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
                "Recuperar Senha - " . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/recuperar"),
                theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-forget", [
            "head" => $head
        ]);
    }

    /**
     * SITE FORGET RESET
     * @param array $data
     */
    public function reset(array $data): void {
        if (Auth::user()) {
            redirect("/app");
        }
        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data["password"]) || empty($data["password_re"])) {
                $json["message"] = $this->message->info("Informe e repita a senha para continuar")->render();
                echo json_encode($json);
                return;
            }

            list($email, $code) = explode("|", $data["code"]);
            $auth = new Auth();

            if ($auth->reset($email, $code, $data["password"], $data["password_re"])) {
                $this->message->success("Senha alterada com sucesso. Vamos controlar?")->flash();
                $json["redirect"] = url("/entrar");
            } else {
                $json["message"] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
                "Crie sua nova senha no " . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/recuperar"),
                theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-reset", [
            "head" => $head,
            "code" => $data["code"]
        ]);
    }

    /**
     * SITE REGISTER
     * @param null|array $data
     * @return void
     */
    public function register(?array $data): void {
        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, por favor, utilize o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (in_array("", $data)) {
                $json['message'] = $this->message->info("Informe seus dados para criar sua conta.")->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();
            $user = new User();
            $user->bootstrap(
                    $data['first_name'],
                    $data['last_name'],
                    $data['email'],
                    $data['password']
            );

            if ($auth->register($user)) {
                $json['redirect'] = url("/confirma");
            } else {
                $json['message'] = $auth->message()->before("Ooops! ")->render();
            }
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
                "Criar Conta - " . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/cadastrar"),
                theme("/assets/images/share.jpg")
        );
        echo $this->view->render("auth-register", [
            "head" => $head
        ]);
    }

    /**
     * SITE OPT-IN CONFIRM
     */
    public function confirm(): void {
        $head = $this->seo->render(
                "Contato - " . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/obrigado"),
                theme("/assets/images/share.jpg")
        );
        echo $this->view->render("optin", [
            "head" => $head,
            "data" => (object) [
                "title" => "Obrigado pelo seu contato.",
                "desc" => "Em breve estaremos respondendo a sua solicitação e esperamos que você comece a utilizar o existaControl em breve!",
                "image" => theme("/assets/images/optin-confirm.jpg")
            ]
        ]);
    }

    /**
     * SITE OPT-IN SUCCESS
     * @param array $data
     */
    public function success(array $data): void {
        $email = base64_decode($data["email"]);
        $user = (new User())->findByEmail($email);

        if ($user && $user->status != "confirmed") {
            $user->status = "confirmed";
            $user->save();
        }

        $head = $this->seo->render(
                "Bem-vindo(a) ao " . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/obrigado"),
                theme("/assets/images/share.jpg")
        );

        echo $this->view->render("optin", [
            "head" => $head,
            "data" => (object) [
                "title" => "Tudo pronto. Você já pode controlar :)",
                "desc" => "Bem-vindo(a) ao seu controle de contas de água",
                "image" => theme("/assets/images/optin-success.jpg"),
                "link" => url("/entrar"),
                "linkTitle" => "Fazer Login"
            ]
        ]);
    }

    /**
     * SITE TERMS
     */
    public function terms(): void {
        $head = $this->seo->render(
                CONF_SITE_NAME . " - Termos de uso",
                CONF_SITE_DESC,
                url("/termos"),
                theme("/assets/images/share.jpg")
        );
        echo $this->view->render("terms", [
            "head" => $head
        ]);
    }

    /**
     * SITE NAV ERROR
     */
    public function error(array $data): void {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $protocol = (isset($_SERVER["SERVER_PROTOCOL"]) ? $_SERVER["SERVER_PROTOCOL"] : "HTTP/1.0");
        $error = new \stdClass();

        switch ($data['errcode']) {
            case "problemas":
                $error->code = "OPS";
                $error->title = "Estamos enfrentando problemas!";
                $error->message = "Parece que nosso serviço não está disponível. Já estamos vendo isso mas caso precise, envie um e-mail :)";
                $error->linkTitle = "ENVIAR E-MAIL";
                $error->link = "mailto:" . CONF_MAIL_SUPPORT;
                $error->httpResponseCode = header($protocol . " 503 Service Unavailable");
                break;
            case "manutencao":
                $error->code = "OPS";
                $error->title = "Desculpe. Estamos em manutenção!";
                $error->message = "Voltamos logo! Por hora estamos trabalhando para melhorar nosso conteúdo para você controlar melhor as suas contas :P";
                $error->linkTitle = null;
                $error->link = null;
                $error->httpResponseCode = header($protocol . " 503 Service Unavailable");
                break;
            default:
                $error->code = $data['errcode'];
                $error->title = "Ooops. Conteúdo indisponível :/";
                $error->message = "Sentimos muito, mas o conteúdo que você tentou acessar não existe, está indisponível no momento ou foi removido :/";
                $error->linkTitle = "Continue navegando!";
                $error->link = url_back();
                $error->httpResponseCode = header($protocol . ($data['errcode'] == 405 ? " 405 Method Not Allowed" : " 404 Not Found"));
                break;
        }

        $head = $this->seo->render(
                "{$error->code} | {$error->title}",
                $error->message,
                url("/ops/{$error->code}"),
                theme("/assets/images/share.jpg"),
                false
        );
        echo $this->view->render("error", [
            "head" => $head,
            "error" => $error
        ]);
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function contact(array $data): void {
        if (empty($data)) {
            $json["message"] = $this->message->warning("Para enviar escreva sua mensagem.")->render();
            echo json_encode($json);
            return;
        }

        if (request_limit("appcontact", 3, 60 * 5)) {
            $json["message"] = $this->message->warning("Por favor, aguarde 5 minutos para enviar novos contatos")->render();
            echo json_encode($json);
            return;
        }

        $subject = "Contato";
        $message = filter_var(
                "Condomínio: " . $data["condominium"] . " / "
                . "Endereço: " . $data["address"] . " / "
                . "Cidade: " . $data["city"] . " / "
                . "Administradora: " . $data["manager"] . " / "
                . "Qtd Apartamentos: " . $data["apartments"] . " / "
                . "Qtd Blocos: " . $data["blocks"] . " / "
                . "Idade do condomínio: " . $data["age"] . " / "
                . "Metrial: " . $data["metrial"] . " / "
                . "Qtd Colunas de água: " . $data["water_columns"] . " / "
                . "Nome do Síndico: " . $data["name_manager"] . " / "
                . "Nome do solicitante: " . $data["requester"] . " / "
                . "Função do solicitante: " . $data["requester_function"] . " / "
                . "Telefone: " . $data["phone"] . " / "
                . "e-mail: " . $data["email"]
                , FILTER_SANITIZE_STRING);

        $view = new View(__DIR__ . "/../../shared/views/email");
        $body = $view->render("mail", [
            "subject" => "Contato",
            "message" => str_textarea($message)
        ]);

        $email = (new Email());

        if ($email->bootstrap(
                        $subject,
                        $body,
                        CONF_MAIL_SUPPORT,
                        "Contato " . CONF_SITE_NAME
                )->queue($data['email'], "{$data['requester']}")) {


            $json['redirect'] = url("/obrigado");
        }
        echo json_encode($json);
        return;
    }

    public function optin(): void {
        $head = $this->seo->render(
                CONF_SITE_NAME . " - Contato",
                CONF_SITE_DESC,
                url("/termos"),
                theme("/assets/images/share.jpg")
        );
        echo $this->view->render("contact", [
            "head" => $head
        ]);
    }

}
