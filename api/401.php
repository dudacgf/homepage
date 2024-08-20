<?php
require_once('../common.php');

$unauthorizedAPICall = str_replace(INCLUDE_PATH, '', $_SERVER['REQUEST_URI']);

$homepage->assign('response', '{"status": "error", "message": "Requisição API não autorizada: ' . $unauthorizedAPICall . '"}');
$homepage->display('response.tpl');

?>
