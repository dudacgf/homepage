<?php
/**
 * carregarFonte.php 
 * salva e processa um arquivo zip contendo configuração e arquivos de fontes recebido via post
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
use Shiresco\Homepage\Temas as Temas;

// se não foi passado nenhum arquivo, morre
$count = count($_FILES['inputFile']['name']);
if ($count > 0) {
	$_tmpFile  = $_FILES['inputFile']['tmp_name'][0];
    $_uploadFile = HOMEPAGE_PATH . '/download/temp/' . $_FILES['inputFile']['name'][0];

    try {
        if (is_uploaded_file($_tmpFile) and move_uploaded_file($_tmpFile, $_uploadFile))
            try {
                if (mime_content_type($_uploadFile) == 'application/zip') {
                        $result = processarArquivo($_uploadFile);
                        if ($result) 
                            $homepage->assign('response', '{"status": "success", "message": "Arquivo [' . 
                                htmlentities($_uploadFile) . ' — ' . mime_content_type($_uploadFile) . 
                                '] salvo", "resultadoProcesso": ' . json_encode($result) . '}');
                        else
                            $homepage->assign('response', '{"status": "error", "message": "Erro ao processar arquivo"}');
                } else 
                    $homepage->assign('response', '{"status": "error", "message": "Tipo de arquivo incorreto. Deve-se passar um zip."}');
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


function processarArquivo($_zipfile) {
    $zip = new ZipArchive;
    if (!$zip->open($_zipfile))
        return False;

    // cria um diretório temporário, extrai os arquivos e abre o json para processamento
    $temp_dir = HOMEPAGE_PATH . '/download/temp/' . bin2hex(random_bytes(10));
    $old_umask = umask(0);
    mkdir($temp_dir, 0700);
    umask($old_umask);
    $zip->extractTo($temp_dir);
    $json_file = trim(shell_exec('ls ' . $temp_dir . '/*.json'));
    $font_info = json_decode(file_get_contents($json_file));

    // grava o registro da fonte no banco de dados
    $f = new Temas\Fonte();
    $f->nome = $font_info->font->{'family name'};
    $f->descricao = $font_info->font->descricao;
    $f->cssfile = $font_info->font->css;
    $f->tipo = $font_info->font->tipo;
    if (!$f->inserir())
        return False;

    foreach ($font_info->font->files as $file) 
        copy($temp_dir . '/' . $file, HOMEPAGE_PATH . '/webfonts/' . $file);

    copy($temp_dir . '/' . $font_info->font->css, HOMEPAGE_PATH . '/estilos/' . $font_info->font->css);

    return array('family_name' => $font_info->font->{'family name'}, 
        'tipo' => $font_info->font->tipo,
        'variantes' => sizeof($font_info->font->files));
}
?>
