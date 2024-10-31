<?php
require_once('auth_force.php');
include('../configs/connection.php');

$dbHost = $connectionInfo['dbHost'];
$dbUser = $connectionInfo['dbUser'];
$dbPassword = $connectionInfo['dbPassword'];
$dbSchema = $connectionInfo['dbSchema'];

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
