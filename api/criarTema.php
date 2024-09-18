<?php
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;
use Shiresco\Homepage\Pagina as Pagina;

if (!isset($requests['nome']) or !isset($requests['comentario']) or !isset($requests['temaBase']))
    $homepage->assign('response', 
        '{"status": "warning", "message": "Faltam informações: preciso de um nome, uma descrição e um tema base"}');
else {
    $tema = new Temas\Temas(NULL);
    $tema->nome = (string) $_REQUEST['nome'];
    $tema->comentario = (string) $_REQUEST['comentario'];
    try {
        $_idTema = $tema->inserir();
        $_temaFrom = Temas\Temas::obterPorNome($requests['temaBase']);
        Temas\TemaCSS::duplicar($_temaFrom->id, $_idTema);
        $homepage->assign('response', '{"status: "success", "message": "Tema ["' . 
            $global_hpDB->real_escape_string($tema->nome) . "] criado com sucesso!");
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "warning", "message": 
            "Erro ao criar o tema [' . $global_hpDB->real_escape_string($tema->nome) . ']: ' . 
            $e->getMessage(). '"}');
    }
}
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->display('response.tpl');
?>
