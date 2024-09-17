<?php
require('../common.php');

$admPag = new pagina(ID_ADM_PAG);

$homepage->assign('admin_area', true);
$homepage->assign('temaPagina', $admPag->temaPagina);
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->display('admin/login.tpl');
?>
