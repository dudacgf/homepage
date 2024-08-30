<?php
require_once('auth_force.php');
require_once('../common.php');

// localização do xml com detalhes da conexão e o número da conexão a ser utilizada...
$connection_info_xml_path = $config_path . 'connections.xml';
$connection_info_xml_id = 1;
$connections = simplexml_load_file($connection_info_xml_path);

foreach ($connections as $connection)
{
    if ($connection['ID'] == $connection_info_xml_id)
    {
        $dbHost = (string) $connection->dbHost;
        $dbUser = (string) $connection->dbUser;
        $dbPassword = (string) $connection->dbPassword;
        $dbSchema  = (string) $connection->dbSchema;
    }
}

$filename = 'homepage-' . date('Ymd') . '.bkp.sql';
$fileWithPath = HOMEPAGE_PATH . '/backup/' . $filename;
$mysqldumpCmd = 'mysqldump --user ' . $dbUser . ' -p\'' . $dbPassword . '\' -h ' . $dbHost . ' -i -A --add-drop-table --add-drop-trigger -r ' . $fileWithPath;

$output = exec($mysqldumpCmd);

if (php_sapi_name() != "cli") {
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename='.$filename);
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fileWithPath));
    header("Content-Type: text/plain");
    readfile($fileWithPath);
}

?>
