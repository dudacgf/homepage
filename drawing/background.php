<?php

header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+3 hour" ) ));
header('Content-type: image/png');

$background=imagecreatefromgif('../imagens/white_bkg.gif');

$color=imagecolorallocate($background, $_REQUEST['r'], $_REQUEST['g'], $_REQUEST['b']);

// de círculo em círculo
imagefill($background, 43, 175,$color);
imagefill($background, 175, 175,$color);
imagefill($background, 275, 175,$color);
imagefill($background, 330, 175,$color);
imagefill($background, 363, 175,$color);

// canto superior esquerdo
imagefill($background, 540, 10, $color);

imagepng($background); 

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 
	
?>
