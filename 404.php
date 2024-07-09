<?php

require_once('common.php');

$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->display("404.tpl");

?>
