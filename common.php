<?php
require_once('vendor/autoload.php');
use Shiresco\Homepage\Database as DB;
use Shiresco\Homepage\HpSmarty as HPS;

// constantes
define('HOMEPAGE_PATH', __dir__ . '/');
define('INCLUDE_PATH', str_replace($_SERVER['DOCUMENT_ROOT'], '', HOMEPAGE_PATH));
define('ID_ADM_PAG', 5);
define('ID_COR_PAG', 7);

// diretório para uploads...
$uploaddir = HOMEPAGE_PATH . 'download/';

// localização dos includes
$include_path = HOMEPAGE_PATH . 'includes/';

// localização dos fortunes
$fortune_path = HOMEPAGE_PATH . 'fortunes/';

// localização das imagens
$images_path = HOMEPAGE_PATH . 'imagens/';

// localização dos arquivos de language
$language_path = HOMEPAGE_PATH . 'language/';

// localização do diretório de configuração
$config_path = HOMEPAGE_PATH . 'configs/';

// localização do diretório de páginas administrativas
$admin_path = HOMEPAGE_PATH . 'admin/';

// resolve qual grupo de variáveis super-globais que trazem os parâmetros da url-query 
$requests = array_merge($_REQUEST, (isset($_GET)? $_GET : (isset($_POST)? $_POST: [])));

// Crio a homepage
$homepage = new HPS\HpSmarty();

// se for página administrativa, le o arquivo de linguagem
if (preg_match('/\/admin\/|\/api\//', $_SERVER['SCRIPT_NAME'])) 
{
    include($language_path . "lang_homepage_admin.php");
    global $lang;
    $homepage->assign('LANG', $lang);
}

// classes específicas da homepage
include_once($include_path . 'class_homepage.php');

// abre a conexao ao DB. A conexão será unica e global
$global_hpDB = new DB\Database();

// função para enviar alertas no reload de páginas (script2reload ou window_close)
if (! function_exists('prepararToast')) {
    function prepararToast($iconAlerta, $msgAlerta) {
        $options = array('expires'=>time()+5, 'path'=>INCLUDE_PATH, 'domain'=>$_SERVER['SERVER_NAME'], 'secure'=>true, 'httponly'=>false, 'SameSite'=>'Strict');
        setcookie('iconAlerta', $iconAlerta, $options);
        setcookie('msgAlerta', $msgAlerta, $options);
        setcookie('showAlerta', 1, $options);
    }
}
?>
