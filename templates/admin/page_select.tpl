{include file="page_header.tpl"}
<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)}onload="createToast({$smarty.cookies.iconAlerta|default:'info'}, '{$smarty.cookies.msgAlerta}');"{/if}>
{include file="admin/menu.tpl"}
{if $displayImagemTitulo == '1'}<div class="logo"><img src='{$includePATH}imagens/logo_shires.png'/ ></div>{/if}
<div  class="titulo">{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</div>
<script type="text/javascript">
<!--
    function doAction(pressed) {ldelim}
        if (pressed == '{$LANG.confirmar}') {ldelim}
            document.cdel.action = '{$includePATH}admin/page_edit.php?mode=edPag&id=' + document.getElementById('id').value;
        {rdelim} 
        else if (pressed == '{$LANG.novaPagina}') {ldelim}
            document.cdel.action = '{$includePATH}admin/page_edit.php?mode=nwPag';
        {rdelim} 
        else if (pressed == '{$LANG.voltar}') {ldelim}
            document.cdel.action = '{$includePATH}admin/index.php';
        {rdelim}
        document.cdel.submit();
    {rdelim}
-->
</script>
<form name="cdel" action="{$includePATH}admin/page_edit.php" method="POST">
    <input type="hidden" id="id" value="" />
    <div class="contentSelecao">
        <div class="tituloSelecao">
            {$LANG.selecionarPagina}
        </div>
        <div class="contornoSelecao">
            <div class="boxSelecao" id="boxSelecao">
                {section name=pag loop=$paginas}
                <label class="boxSelecaoLabel">
                <input class="boxRadio noselect" type="radio"  id="{$paginas[pag].idPagina}" name="selectPagina" value="{$paginas[pag].idPagina}" onClick="document.getElementById('id').value = this.value;" style="user-select: none;"/>
                    {$paginas[pag].tituloPagina} [{$paginas[pag].tituloTabela}]
                </label> 
                {/section}
            </div>
        </div>
        <div class="interior" style="text-align: center; padding-top: 4pt; margin: 1.5rem;">
            <input type="submit" class="submit" name="go" value="{$LANG.confirmar}" onclick="javascript: doAction(this.value);" /> ::
            <input type="submit" class="submit" name="go" value="{$LANG.novaPagina}" onclick="javascript: doAction(this.value);" /> ::
            <input type="submit" class="submit" name="go" value="{$LANG.voltar}" onclick="javascript: doAction(this.value);" />
        </div>
    </div>
</form>
{include file="page_footer.tpl"}
