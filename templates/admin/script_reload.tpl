<html>
<head>
<meta http-equiv="Refresh" content="0;url={$includePATH}{$script2reload}?{if isset($scriptMode)}mode={$scriptMode}{/if}{if isset($idPagina)}&id={$idPagina}{/if}{if isset($idCategoria)}&idCat={$idCategoria}{/if}{if isset($idGrupo)}&idGrp={$idGrupo}{/if}{if isset($scriptGR)}&gr={$scriptGR}{/if}" />
</head>
<body>
{if isset($msgAlerta)}
<script type="text/javascript">alert('{$msgAlerta}');</script>
{/if}
</body>
<html>

{* vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
