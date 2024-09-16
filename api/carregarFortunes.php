<?php
/**
 * carregarFortunes.php 
 * salva e processa um arquivo de citações recebido via post
 *
 * recebe: o arquivo como uma entrada da special var $_FILES
 * devolve: json resposta contendo status, mensagem informativa e, 
 *          se status == 'success', um array com os resultados do processamento
 */
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('auth_force.php');
include_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;
use Shiresco\Homepage\Fortunes as Fortunes;

// se não foi passado nenhum arquivo, morre
$count = count($_FILES['inputFile']['name']);
if ($count > 0) {
	$_tmpFile  = $_FILES['inputFile']['tmp_name'][0];
    $_uploadFile = $uploaddir . 'fortunes/' . $_FILES['inputFile']['name'][0];

    try {
        if (is_uploaded_file($_tmpFile) and move_uploaded_file($_tmpFile, $_uploadFile))
            try {
                switch (mime_content_type($_uploadFile)) {
                    case 'text/plain':
                        $result = Fortunes\Fortunes::processarArquivoQuote($_uploadFile);
                        $homepage->assign('response', '{"status": "success", "message": "Arquivo [' . 
                            htmlentities($_uploadFile) . ' — ' . mime_content_type($_uploadFile) . 
                            '] salvo", "resultadoProcesso": ' . json_encode($result) . '}');
                        break;
                    case 'text/csv':
                        $homepage->assign('response', '{"status": "info", 
                            "message": "ainda nao consigo processar esse tipo de arquivo"}');
                        break;
                    case 'text/json':
                    case 'application/json':
                        $homepage->assign('response', '{"status": "info", 
                            "message": "ainda nao consigo processar esse tipo de arquivo"}');
                        break;
                    default:
                        $homepage->assign('response', '{"status": "error", 
                            "message": "Tipo de arquivo incorreto"}');
                }
            } catch (Exception $e) {
                $homepage->assign('response', '{"status": "error", "message": "Erro ao processar arquivo:' . $e->getMessage() . '"}');
            }
        else
            $homepage->assign('response', '{"status": "error", "message": "Erro ao salvar arquivo"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao salvar arquivo"}');
    }
} else
    $homepage->assign('response', '{"status": "error", "message": "Nenhum arquivo carregado"}');

$homepage->display('response.tpl');
?>
