<?php

date_default_timezone_set("America/Sao_Paulo");

/**
 * DATABASE
 */

if (strpos($_SERVER['HTTP_HOST'], "localhost")) {
    define("CONF_DB_HOST", "localhost");
    define("CONF_DB_USER", "root");
    define("CONF_DB_PASS", "");
    define("CONF_DB_NAME", "");
} else {
    define("CONF_DB_HOST", "");
    define("CONF_DB_USER", "");
    define("CONF_DB_PASS", "");
    define("CONF_DB_NAME", "");
}

/**
 * PROJECT URLs
 */
define("CONF_URL_BASE", "https://www.existacontrol.com.br");
define("CONF_URL_TEST", "https://www.localhost/existacontrol");

/**
 * SITE
 */
define("CONF_SITE_NAME", "existaControl");
define("CONF_SITE_TITLE", "Medições e Relatórios");
define("CONF_SITE_DESC", "O existaControl é um gerenciador de medições e relatórios de água simples e poderoso. O prazer de ter o controle total de seus demonstrativos de consumo de água.");
define("CONF_SITE_LANG", "pt_BR");
define("CONF_SITE_DOMAIN", "existacontrol.com.br");
define("CONF_SITE_ADDR_STREET", "Rua Carlos Palut");
define("CONF_SITE_ADDR_NUMBER", "592");
define("CONF_SITE_ADDR_COMPLEMENT", "BL C 4");
define("CONF_SITE_ADDR_CITY", "Rio de Janeiro");
define("CONF_SITE_ADDR_STATE", "RJ");
define("CONF_SITE_ADDR_ZIPCODE", "22.710-310");

/**
 * DATES
 */
define("CONF_DATE_BR", "d/m/Y H:i:s");
define("CONF_DATE_APP", "Y-m-d H:i:s");

/**
 * PASSWORD
 */
define("CONF_PASSWD_MIN_LEN", 8);
define("CONF_PASSWD_MAX_LEN", 40);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTION", ["cost" => 10]);

/**
 * VIEW
 */
define("CONF_VIEW_PATH", __DIR__ . "/../../shared/views");
define("CONF_VIEW_EXT", "php");
define("CONF_VIEW_THEME", "waterweb");
define("CONF_VIEW_APP", "waterapp");
define("CONF_VIEW_ADMIN", "wateradm");

/**
 * UPLOAD
 */
define("CONF_UPLOAD_DIR", "storage");
define("CONF_UPLOAD_IMAGE_DIR", "images");
define("CONF_UPLOAD_FILE_DIR", "files");
define("CONF_UPLOAD_MEDIA_DIR", "medias");

/**
 * IMAGES
 */
define("CONF_IMAGE_CACHE", CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache");
define("CONF_IMAGE_SIZE", 2000);
define("CONF_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);

/**
 * MAIL
 */
define("CONF_MAIL_HOST", "");
define("CONF_MAIL_PORT", "465");
define("CONF_MAIL_USER", "");
define("CONF_MAIL_PASS", "");
define("CONF_MAIL_SENDER", ["name" => "existaControl", "address" => ""]);
define("CONF_MAIL_SUPPORT", "");
define("CONF_MAIL_OPTION_LANG", "br");
define("CONF_MAIL_OPTION_HTML", true);
define("CONF_MAIL_OPTION_AUTH", true);
define("CONF_MAIL_OPTION_SECURE", "ssl");
define("CONF_MAIL_OPTION_CHARSET", "utf-8");
