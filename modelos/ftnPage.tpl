<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Type" content="image/jpeg" />
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/base.css" title="default" />
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/{$classPagina}.css" title="default" />
<style type="text/css">
#theBody { color: #202020; } body.{$classPagina} div.titulo { color: #c6b9a6; } body.{$classPagina} div.fortune { color: #e4dcbb; } body.{$classPagina} div.tituloCategoria { color: #e4dcbb; } body.{$classPagina} div.tituloLateral { color: #e4dcbb; } body.{$classPagina} div.itemLateral { color: #e4dcbb; } body.{$classPagina} div.subTitulo { color: #e4dcbb; } body.{$classPagina} div.coluna { color: #e4dcbb; } body.{$classPagina} div.tituloColuna { color: #e4dcbb; } body.{$classPagina} div.headexpandable { color: #e4dcbb; } body.{$classPagina} div.headclickable { color: #e4dcbb; } body.{$classPagina} input.submit { color: #e4dcbb; } body.{$classPagina} div.expanded a { color: #202020; } body.{$classPagina} div.expandable:hover a { color: #202020; } body.{$classPagina} div.interior:hover a { color: #202020; } body.{$classPagina} a:hover { background-color: #e4dcbb; } body.{$classPagina} div.expanded a:hover { background-color: #e4dcbb; } body.{$classPagina} div.expandable:hover a:hover { background-color: #e4dcbb; } body.{$classPagina} div.interior:hover a:hover { background-color: #e4dcbb; } 
</style>
</head>
<body class="{$classPagina}" style=" overflow: none; background-color: #404040; background-image: none;" id="theBody" >
	<div id="fortune" style="width: 100%; height: 120px; clear: both; float: left; padding=10px; margin=10px;">
		<div class="fortune">
			<br />{$fortuneCookie}
        </div>
	</div>
</body>
</html>
