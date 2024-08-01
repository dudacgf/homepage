<?php
require_once('common.php');
include($language_path . "lang_homepage_admin.php");

// arruma paths em .htaccess
check_root_htaccess();
check_admin_htaccess();

$root_ht = file_get_contents(HOMEPAGE_PATH . '/.htaccess');
$homepage->assign('root_ht', $root_ht);

$admin_ht = file_get_contents(HOMEPAGE_PATH . '/admin/.htaccess');
$homepage->assign('admin_ht', $admin_ht);

// obtém a página administrativa
$admPag = new pagina(ID_ADM_PAG);

// títulos
$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaInstall']);
$homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaInstall']);
$homepage->assign('classPagina', $admPag->classPagina);

// le os cookies e passa para a página a ser carregada.
$cookedStyles = '';
$colorCookies = cookedStyle::getArray(5);
if ($colorCookies) 
{
    foreach ($colorCookies as $selector => $colorCookie) {
        $cookedStyles .= implode("\n", $colorCookie) . "\n}\n";
    }
}
$homepage->assign('cookedStyles', $cookedStyles);

$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('imagesPATH', $images_path);
$homepage->display('admin/post_install.tpl');

function check_admin_htaccess() {
    $ADMINhtaccessFilePath = HOMEPAGE_PATH . '/admin/.htaccess';
    $htaccessFileContents = file_get_contents($ADMINhtaccessFilePath);
    $newAuthFileLine = 'authuserfile ' . HOMEPAGE_PATH . 'admin/.htpasswd.ghtpasswd';
    if (!$htaccessFileContents) {
        $F = fopen($ADMINhtaccessFilePath, 'w');
        fwrite($F, 'authtype basic' . PHP_EOL);
        fwrite($F, $newAuthFileLine . PHP_EOL);
        fwrite($F, 'authname "Secure Area"' . PHP_EOL);
        fwrite($F, 'require user admin' . PHP_EOL);
    }
    else {
        $newFileContents = preg_replace('/authuserfile .*(\s)/', $newAuthFileLine . '$1', $htaccessFileContents);
        if ($htaccessFileContents != $newFileContents)
        {
            file_put_contents($htaccessFilePath, $newFileContents);
        }
    }
    $APIhtaccessFilePath = HOMEPAGE_PATH . 'api/.htaccess';
    copy($ADMINhtaccessFilePath, $APIhtaccessFilePath);
}

function check_root_htaccess() {
    $htaccessFilePath = HOMEPAGE_PATH . '/.htaccess';
    $htaccessFileContents = file_get_contents($htaccessFilePath);
    $newDocumentErrorLine = 'ErrorDocument 404 ' . INCLUDE_PATH . '404/404.php';
    $newDocumentAPIRewriteLIne = 'RewriteRule ^getinsertpath$ ' . INCLUDE_PATH . 'api/getinsertpath.php [NC]';

    if ((!$htaccessFileContents) or ($htaccessFileContents != preg_replace('/ErrorDocument.*(\s)/', $newDocumentErrorLine . '$1', $htaccessFileContents))) {
        $F = fopen($htaccessFilePath, 'w');
        if ($F) {
            fwrite($F, $newDocumentErrorLine . PHP_EOL);
            fwrite($F, PHP_EOL);
            fwrite($F, 'RedirectMatch 404 configs/connections.xml' . PHP_EOL);
            fwrite($F, 'RedirectMatch 404 download/*' . PHP_EOL);
            fwrite($F, 'RedirectMatch 404 includes/*' . PHP_EOL);
            fwrite($F, 'RedirectMatch 404 language/*' . PHP_EOL);
            fwrite($F, PHP_EOL);
            fwrite($F, $newDocumentAPIRewriteLIne . PHP_EOL);
        } else 
            die("error opening $htaccessFilePath");
    }
}

?>
