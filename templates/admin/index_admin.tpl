<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
<head>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/estilos.css" title="default" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<title>{$tituloPagina}</title>
</head>

<frameset cols="280,*" rows="*" border="2" framespacing="0" frameborder="yes"> 
	<frame src="{$includePATH}admin/homepage_admin.php?id=5&navpanel=yes" name="_nav_panel" marginwidth="3" marginheight="3" scrolling="no">
	<frame src="{$includePATH}admin/estatisticas.php" name="_admin_panel" marginwidth="10" marginheight="10" scrolling="auto">
</frameset>

<noframes>
	<body bgcolor="#FFFFFF" text="#000000">
		<p>Sorry, your browser doesn't seem to support frames</p>
	</body>
</noframes>
</html>

