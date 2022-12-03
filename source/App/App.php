<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Core\View;
use Source\Models\Auth;
use Source\Models\WaterApp\AppInvoice;
use Source\Models\WaterApp\AppViewInvoice;
use Source\Models\WaterApp\AppInvoiceCondominium;
use Source\Models\WaterApp\AppCondominium;
use Source\Models\WaterApp\AppSubscription;
use Source\Models\WaterApp\AppSubscriptionCondominium;
use Source\Models\Post;
use Source\Models\Report\Access;
use Source\Models\Report\Online;
use Source\Models\User;
use Source\Support\Email;
use Source\Support\Thumb;
use Source\Support\Upload;
use Source\Support\Pager;
use Source\Models\WaterApp\AppApartment;

/**
 * Class App
 * @package Source\App
 */
class App extends Controller
{

    /** @var User */
    private $user;

    /**
     * App constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_APP . "/");

        if (!$this->user = Auth::user()) {
            $this->message->warning("Efetue login para acessar o APP.")->flash();
            redirect("/entrar");
        }

        (new Access())->report();
        (new Online())->report();
    }

    /**
     * APP HOME
     */
    public function home(): void
    {
        $head = $this->seo->render(
            "Olá {$this->user->first_name}. Vamos controlar? - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );
        $user = (new User())->findById($this->user->id);

        if ($user->level != 2) {

            //CHART
            $chartData = (new AppInvoice())->chartData($this->user);
            //END CHART
            //INCOME && EXPENSE       

            $subscription = (new AppSubscription())->find("user_id = :user_id", "user_id={$user->id}")->fetch();
            $consumption = null;
            $invoice = null;
            if (!empty($subscription->id)) {
                $consumption = (new AppInvoice())
                    ->find(
                        "subscription_id = :subscription_id "
                            . "AND date(due_at) <= date(now() + INTERVAL 1 MONTH) ",
                        "subscription_id={$subscription->id}"
                    )
                    ->limit(3)
                    ->order("due_at DESC")
                    ->fetch(true);

                $invoice = (new AppInvoice())
                    ->find(
                        "subscription_id = :subscription_id "
                            . "AND date(due_at) <= date(now() + INTERVAL 1 MONTH) ",
                        "subscription_id={$subscription->id}"
                    )
                    ->limit(3)
                    ->order("due_at DESC")
                    ->fetch(true);
            }
            //END INCOME && EXPENSE
            //AVARAGE
            $avarege = (new AppInvoice())->balance($this->user);
            //END AVARAGE
        } else {
            //CHART
            $chartData = (new AppInvoiceCondominium())->chartDataCondominium($this->user);
            //END CHART
            //INCOME && EXPENSE    
            $subscription = (new AppSubscriptionCondominium())->find("user_id = :user_id", "user_id={$user->id}")->fetch();

            $consumption = null;
            $invoice = null;
            if (!empty($subscription->id)) {
                $consumption = (new AppInvoiceCondominium())
                    ->find(
                        "condominium_id = :condominium_id "
                            . "AND date(due_at) <= date(now() + INTERVAL 1 MONTH) ",
                        "condominium_id={$subscription->condominium_id}"
                    )
                    ->limit(3)
                    ->order("due_at DESC")
                    ->fetch(true);

                $invoice = (new AppInvoiceCondominium())
                    ->find(
                        "condominium_id = :condominium_id "
                            . "AND date(due_at) <= date(now() + INTERVAL 1 MONTH) ",
                        "condominium_id={$subscription->condominium_id}"
                    )
                    ->limit(3)
                    ->order("due_at DESC")
                    ->fetch(true);
            }
            //END INCOME && EXPENSE
            //AVARAGE
            $avarege = (new AppInvoiceCondominium())->balanceCondominium($this->user);
            //END AVARAGE
        }
        //POSTS
        $posts = (new Post())->findPost()->limit(3)->order("post_at DESC")->fetch(true);
        //END POSTS

        echo $this->view->render("home", [
            "head" => $head,
            "chart" => $chartData,
            "consumptions" => $consumption,
            "invoices" => $invoice,
            "avarage" => $avarege,
            "services" => $posts,
            "user" => $user
        ]);
    }

    /**
     * @param array|null $data
     */
    public function income(?array $data): void
    {
        $head = $this->seo->render(
            "Meu Histórico - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        $invoices = null;

        if ($this->user->level != 2) {
            $subscription = (new AppSubscription())->find("user_id = :user_id", "user_id={$this->user->id}")->fetch();
            if (!empty($subscription->id)) {
                $invoices = (new AppInvoice())
                    ->find("subscription_id = :subscription_id", "subscription_id={$subscription->id}")->order('due_at DESC');
            } else {
                $this->message->error("Ooops! Você não possui acesso ao histórico por não possuir uma assinatura.")->flash();
                redirect("/app");
            }
        } else {
            $app_subscription_condominiun = (new AppSubscriptionCondominium())
                ->find("user_id = :user_id", "user_id={$this->user->id}")->fetch();
            if ($app_subscription_condominiun) {
                $condominium = (new AppCondominium())->find("id = {$app_subscription_condominiun->condominium_id}")->fetch();
                $invoices = (new AppViewInvoice())
                    ->find("subscription_id IN ("
                        . "SELECT id FROM app_subscription WHERE apartment_id IN ("
                        . "SELECT id FROM app_apartment WHERE condominium_id = {$condominium->id}))");
            } else {
                $this->message->error("Ooops! Você não possui acesso ao histórico de moradores")->flash();
                redirect("/app");
            }
        }

        $pager = new Pager(url("/app/historico/p/"));
        $pager->pager(($invoices != null ? $invoices->count() : 0), 12, ($data['page'] ?? 1));

        echo $this->view->render("invoices", [
            "user" => $this->user,
            "head" => $head,
            "invoices" => $invoices
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array|null $data
     */
    public function incomeCondominium(?array $data): void
    {
        $head = $this->seo->render(
            "Histórico Condomínio - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        $invoices = null;

        $app_subscription_condominiun = (new AppSubscriptionCondominium())
            ->find("user_id = :user_id", "user_id={$this->user->id}")->fetch();
        if ($app_subscription_condominiun) {
            $condominium = (new AppCondominium())->find("id = {$app_subscription_condominiun->condominium_id}")->fetch();

            if (empty($condominium)) {
                $this->message->error("Ooops! Condomínio não encontrado")->flash();
                redirect("/app");
            }

            $invoices = (new AppInvoiceCondominium())
                ->find("condominium_id = {$condominium->id}");
        } else {
            $this->message->error("Ooops! Você não possui acesso ao histórico do condomínio")->flash();
            redirect("/app");
        }

        $pager = new Pager(url("/app/historico-condominio/p/"));
        $pager->pager($invoices->count(), 12, ($data['page'] ?? 1));

        echo $this->view->render("invoicesCondominium", [
            "user" => $this->user,
            "head" => $head,
            "invoices" => $invoices->order('due_at DESC')
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function support(array $data): void
    {
        if (empty($data["message"])) {
            $json["message"] = $this->message->warning("Para enviar escreva sua mensagem.")->render();
            echo json_encode($json);
            return;
        }

        if (request_limit("appsupport", 3, 60 * 5)) {
            $json["message"] = $this->message->warning("Por favor, aguarde 5 minutos para enviar novos contatos, sugestões ou reclamações")->render();
            echo json_encode($json);
            return;
        }

        if (request_repeat("message", $data["message"])) {
            $json["message"] = $this->message->info("Já recebemos sua solicitação {$this->user->first_name}. Agradecemos pelo contato e responderemos em breve.")->render();
            echo json_encode($json);
            return;
        }

        $subject = date("Y-m-d") . " - {$data["subject"]}";
        $message = filter_var($data["message"], FILTER_SANITIZE_STRING);

        $view = new View(__DIR__ . "/../../shared/views/email");
        $body = $view->render("mail", [
            "subject" => $subject,
            "message" => str_textarea($message)
        ]);

        (new Email())->bootstrap(
            $subject,
            $body,
            CONF_MAIL_SUPPORT,
            "Suporte " . CONF_SITE_NAME
        )->queue($this->user->email, "{$this->user->first_name} {$this->user->last_name}");

        $this->message->success("Recebemos sua solicitação {$this->user->first_name}. Agradecemos pelo contato e responderemos em breve.")->flash();
        $json["reload"] = true;
        echo json_encode($json);
    }

    /**
     * @param array $data
     */
    public function invoice(array $data): void
    {

        $head = $this->seo->render(
            "Demonstrativo - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        if ($this->user->level != 2) {
            $subscription = (new AppSubscription())->find("user_id = :user_id", "user_id={$this->user->id}")->fetch();

            if (!$subscription) {
                $this->message->error("Ooops! Você não possui uma assinatura!")->flash();
                redirect("/app");
            }

            $invoice = (new AppInvoice())->find(
                "subscription_id = :subscription_id AND id = :invoice",
                "subscription_id={$subscription->id}&invoice={$data["invoice"]}"
            )->fetch();
        } else {
            $app_subscription_condominiun = (new AppSubscriptionCondominium())
                ->find("user_id = :user_id", "user_id={$this->user->id}")->fetch();
            $condominium = (new AppCondominium())->find("id = {$app_subscription_condominiun->condominium_id}")->fetch();
            $invoice = (new AppInvoice())
                ->find("subscription_id IN ("
                    . "SELECT id FROM app_subscription WHERE apartment_id IN ("
                    . "SELECT id FROM app_apartment WHERE condominium_id = {$condominium->id})) AND id = {$data["invoice"]}")
                ->fetch();
        }

        if (!$invoice) {
            $this->message->error("Ooops! Você tentou acessar um demonstrativo que não existe")->flash();
            redirect("/app");
        }

        $user = (new User())->findById($this->user->id);

        echo $this->view->render("invoice", [
            "head" => $head,
            "invoice" => $invoice,
            "user" => $user
        ]);
    }

    /**
     * @param array $data
     */
    public function invoiceCondominium(array $data): void
    {

        $head = $this->seo->render(
            "Relatório - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        $app_subscription_condominiun = (new AppSubscriptionCondominium())
            ->find("user_id = :user_id", "user_id={$this->user->id}")->fetch();

        if (!$app_subscription_condominiun) {
            $this->message->error("Ooops! Você tentou acessar um demonstrativo que não existe")->flash();
            redirect("/app");
        }

        $condominium = (new AppCondominium())->find("id = {$app_subscription_condominiun->condominium_id}")->fetch();

        if (!$condominium) {
            $this->message->error("Ooops! Você tentou acessar um demonstrativo que não existe")->flash();
            redirect("/app");
        }

        $invoice = (new AppInvoiceCondominium())
            ->find("condominium_id  = {$condominium->id} AND id = {$data["invoice"]}")
            ->fetch();

        if (!$invoice) {
            $this->message->error("Ooops! Você tentou acessar um demonstrativo que não existe")->flash();
            redirect("/app");
        }

        $units = (new AppApartment())->find("condominium_id = {$condominium->id} AND status = 'active'")->count();

        $user = (new User())->findById($this->user->id);

        echo $this->view->render("invoiceCondominium", [
            "head" => $head,
            "invoice" => $invoice,
            "user" => $user,
            "units" => $units
        ]);
    }

    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function profile(?array $data): void
    {
        if (!empty($data["update"])) {
            list($d, $m, $y) = explode("/", $data["datebirth"]);
            $user = (new User())->findById($this->user->id);
            $user->first_name = $data["first_name"];
            $user->last_name = $data["last_name"];
            $user->genre = $data["genre"];
            $user->datebirth = "{$y}-{$m}-{$d}";
            $user->document = preg_replace("/[^0-9]/", "", $data["document"]);

            if (!empty($_FILES["photo"])) {
                $file = $_FILES["photo"];
                $upload = new Upload();

                if ($this->user->photo()) {
                    (new Thumb())->flush("storage/{$this->user->photo}");
                    $upload->remove("storage/{$this->user->photo}");
                }

                if (!$user->photo = $upload->image($file, "{$user->first_name} {$user->last_name} " . time(), 360)) {
                    $json["message"] = $upload->message()->before("Ooops {$this->user->first_name}! ")->after(".")->render();
                    echo json_encode($json);
                    return;
                }
            }

            if (!empty($data["password"])) {
                if (empty($data["password_re"]) || $data["password"] != $data["password_re"]) {
                    $json["message"] = $this->message->warning("Para alterar sua senha, informa e repita a nova senha!")->render();
                    echo json_encode($json);
                    return;
                }

                $user->password = $data["password"];
            }

            if (!$user->save()) {
                $json["message"] = $user->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Pronto {$this->user->first_name}. Seus dados foram atualizados com sucesso!")->render();
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Meu perfil - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render("profile", [
            "head" => $head,
            "user" => $this->user,
            "photo" => ($this->user->photo() ? image($this->user->photo, 360, 360) :
                theme("/assets/images/avatar.jpg", CONF_VIEW_APP))
        ]);
    }

    /**
     * APP LOGOUT
     */
    public function logout(): void
    {
        $this->message->info("Você saiu com sucesso " . Auth::user()->first_name . ". Volte logo :)")->flash();

        Auth::logout();
        redirect("/entrar");
    }
}
