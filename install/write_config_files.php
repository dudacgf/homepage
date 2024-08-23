<?php
global $auth_file_written;
$auth_file_written = 0;

function write_config_files() {
    // grava os arquivos .htaccess nos diretórios a serem protegidos

    // admin: restrição de autenticação e desvio do 404
    write_htaccess('admin', array('error_404'=>'404/404.php', 'auth_needed'=>true, 'auth_user'=>'admin', 'auth_pw'=>''));
    //write_htaccess('admin', '404/404.php', [], true, $requests['admin_name'], $requests['admin_pw']);

    // api: restrição de autenticação, desvio do 401 e do 404, exceções para acesso a alguns scripts
    write_htaccess('api', array('error_401'=>'api/401.php', 'error_404'=>'api/404.php', 
                                'auth_needed'=>true, 'auth_user'=>'admin', 'auth_pw'=>'',
                                'allowed_files'=>['addColorCookie.php','delColorCookie.php','delAllColorCookies.php','401.php','404.php']));
    //write_htaccess('api', 'api/404.php', [], true, $requests['admin_name'], $requests['admin_pw']);

    // main folder: restricted folders e desvio do 404
    write_htaccess('', array('error_404'=>'404/404.php', 'restricted_folders'=>['/templates','/configs','/download','/language']));
    //write_htaccess('', '404/404.php', ['backup', 'configs', 'download', 'language'], false);
    
    //write_connections($requests['db_name'], $requests['db_host'], $requests['db_user'], $requests['db_pw']);
}

// o arquivo htpasswd sempre vai estar no folder admin/
function write_htpasswd($auth_user, $auth_pw) {
    global $auth_file_written;

    $md5_pw = password_hash($auth_pw, PASSWORD_BCRYPT);
    $contents = $auth_user . ':' . $md5_pw . PHP_EOL;
    file_put_contents(HOMEPAGE_PATH . 'admin/.htpasswd.ghtpasswd', $contents);

    $auth_file_written = 1;
}

/*
 * write_htaccess - grava um arquivo básico de ht_access
 *
 * recebe:
 * $folder - o diretório em que o .htaccess será criado. obrigatório
 * $options - array com opções para a criação do arquivo:
 *     - error_404: página web (dentro desse diretório) que será acionada em caso de erro 404 PAGE NOT FOUND.
 *       default: false, não haverá apontamento de página 404 (assume o do folder superior ou o do apache/nginx)     
 *     - error_401: página web (dentro desse diretório) que será acionada em caso de erro 401 UNAUTHORIZED
 *       default: false, não haverá apontamento de página 401 (assume o do folder superior ou o do apache/nginx)     
 *     - restricted_folders: array com subdiretórios de $folder cujo acesso será restrito com rewrite para erro 404 
 *       default: vazio
 *     - allowed_files: se um diretório for restrito (por autenticação ou por restricted_folder), uma lista de exceções (regex)
 *       default: vazio
 *     - auth_needed: se haverá necessidade de autenticação para acesso ao diretório
 *       default: false
 *     - auth_user: se auth_needed for true, o usuário a quem se permitirá acesso
 *       default: obrigatório caso auth_needed seja TRUE. ignorado em caso contrário
 *     - auth_pw: se auth_needed for true, senha para o usuário auth_user
 *       default: obrigatório caso auth_needed seja TRUE. ignorado em caso contrário
 */
function write_htaccess($folder, $options) {
    global $auth_file_written;

    // verifica valores e aplica defaults, se necessário
    $error_404 = (isset($options['error_404'])? $options['error_404']: false);
    $error_401 = (isset($options['error_401'])? $options['error_401']: false);
    $restricted_folders = (isset($options['restricted_folders'])
        ?(is_array($options['restricted_folders'])
            ?$options['restricted_folders']
            :Throw new Exception ('opção restricted_files precisa ser um array de nomes de diretórios'))
        : []);
    $allowed_files = (isset($options['allowed_files'])
        ?(is_array($options['allowed_files'])
            ?$options['allowed_files']
            :Throw new Exception ('opção allowed_files precisa ser um array contendo regex expressions'))
        : []);
    $auth_needed = (isset($options['auth_needed'])? $options['auth_needed']: false);
    $auth_user = (isset($options['auth_user'])
        ? $options['auth_user'] 
        :(!isset($auth_needed)
            ? Throw new Exception('Preciso de usuário para a autenticação solicitada')
            : '')
    );
    $auth_pw = (isset($options['auth_pw'])
        ? $options['auth_pw'] 
        :(!isset($auth_needed)
            ? Throw new Exception('Preciso de senha para a autenticação solicitada')
            : '')
    );

    // cria uma página temporária do smarty já que vou só fazer um fetch
    $temp_page = new hp_smarty();
    $temp_page->assign('error404', $error_404);
    $temp_page->assign('error401', $error_401);
    $temp_page->assign('restricted_folders', $restricted_folders);
    $temp_page->assign('allowed_files', $allowed_files);
    $temp_page->assign('auth_needed', $auth_needed);
    $temp_page->assign('auth_user', $auth_user);
    $htaccessFileContents = $temp_page->fetch('install/htaccess.tpl');

    // grava o .htaccess
    $htaccessFilePath =  str_replace('//', '/', HOMEPAGE_PATH . $folder . '/.htaccess');
    file_put_contents($htaccessFilePath, $htaccessFileContents);

    // se pediu autenticação e ainda não gravou o arquivo htpasswd, vai lá e grava
    if ($auth_needed and !$auth_file_written) {
        write_htpasswd($auth_user, $auth_pw);
    }
}

function write_connections($db_name, $db_host, $db_user, $db_pw) {
    $temp_page = new hp_smarty();

    $temp_page->assign('db_name', $db_name);
    $temp_page->assign('db_host', $db_host);
    $temp_page->assign('db_user', $db_user);
    $temp_page->assign('db_pw', $db_pw);

    $connectionsContents = $temp_page->fetch('install/connections_xml.tpl');
    $connectionsFilePath = HOMEPAGE_PATH . '/configs/connections.xml';

    file_put_contents($connectionsFilePath, $connectionsContents);
}
?>
