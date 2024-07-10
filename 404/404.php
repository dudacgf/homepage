<?php

require_once('../common.php');

$homepage->addTemplateDir(HOMEPAGE_PATH . '404');
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->display("404.tpl");

?>
