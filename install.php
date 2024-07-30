<?php
require_once('common.php');

// arruma paths em .htaccess
check_root_htaccess();
check_admin_htaccess();

function check_admin_htaccess() {
    $htaccessFilePath = HOMEPAGE_PATH . '/admin/.htaccess';
    $htaccessFileContents = file_get_contents($htaccessFilePath);
    $newAuthFileLine = 'authuserfile ' . HOMEPAGE_PATH . 'admin/.htpasswd.ghtpasswd';
    if (!$htaccessFileContents) {
        $F = fopen($htaccessFilePath, 'w');
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
