<?php
include('../common.php');

$homepage->assign('response', '{"includePATH": "' . INCLUDE_PATH . '"}');
$homepage->display('response.tpl');

?>
