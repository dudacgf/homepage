<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');

require_once('../common.php');

$unknownAPICall = str_replace(INCLUDE_PATH, '', $_SERVER['REQUEST_URI']);

$homepage->assign('response', '{"status": "error", "message": "Requisição API não registrada: ' . $unknownAPICall . '"}');
$homepage->display('response.tpl');

?>
