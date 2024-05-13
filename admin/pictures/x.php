<?php
echo 'carmação';
$x = iconv('iso-8859-1', 'utf-8', 'carmação');
echo "$x";
echo iconv('iso-8859-1', 'utf-8', $x) . '';
?>
