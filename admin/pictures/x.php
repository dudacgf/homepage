<?php
echo 'carma��o';
$x = iconv('iso-8859-1', 'utf-8', 'carma��o');
echo "$x";
echo iconv('iso-8859-1', 'utf-8', $x) . '';
?>
