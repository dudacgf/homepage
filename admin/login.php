<?php
require('../common.php');

$admPag = new pagina(ID_ADM_PAG);

$homepage->assign('classPagina', $admPag->classPagina);
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->display('admin/login.tpl');
?>
