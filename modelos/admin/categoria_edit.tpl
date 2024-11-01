{include file="page_header.tpl"}
<body id="theBody" class="{$temaPagina}"{if isset($smarty.cookies.showAlerta)} onload="createToast('{$smarty.cookies.iconAlerta|default:info}', '{$smarty.cookies.msgAlerta}');"{/if}>
{include file="admin/menu.tpl"}
<div  class="titulo">
  {if $displayImagemTitulo == '1'}<div class="logo">{strip}{$logo_shires}{/strip}</div>{/if}
  <span>{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</span>
</div>
<script type="text/javascript">
    function doAction(pressed) {
        mode = document.getElementById('mode').value;
        if (mode != 'crCat') {
            switch (pressed) {
                case '{$LANG.gravar}':
                    document.getElementById('mode').value = 'svCat';
                    break;
                case '{$LANG.excluir}':
                    response = confirm('Confirma exclusão da categoria?');
                    if (!response) 
                        return;
                    document.getElementById('mode').value = 'exCat';
                    break;
                case '{$LANG.novaCategoria}':
                    document.getElementById('mode').value = 'nwCat';
                    break;
                case '{$LANG.cancelar}':
                    document.getElementById('mode').value = 'slCat';
                    break;
            }
    } else {
        switch (pressed) {
            case '{$LANG.gravar}':
                document.getElementById('mode').value = 'crCat';
                break;
            case '{$LANG.cancelar}':
                document.getElementById('mode').value = 'slCat';
                break;
        }
    }
    document.edCat.submit();
}
</script>

<div class="subTitulo">{$LANG.configuracao}</div>
<form id="edCat" name="edCat" method="POST" action="{$includePATH}admin/categoria_edit.php">
{if $criarCategoria}
	<input type="hidden" id="mode" name="mode" value="crCat" />
{else}
	<input type="hidden" id="mode" name="mode" value="svCat" />
	<input type="hidden" id="id" name="id" value="{$idPagina}" />
	<input type="hidden" id="idCat" name="idCat" value="{$idCategoria}" />
{/if}
	<div class="itemLateral" style="padding: 5px;">{$LANG.hp_categorias_DescricaoCategoria}</div>
	<div class="item" style="padding: 0;">
        <input type="text" class="FormExtra" size=30 name="descricaoCategoria" placeholder="{$LANG.hp_categorias_Placeholder_DescricaoCategoria}" value="{$descricaoCategoria}" tabindex="1" style="padding: 0; padding-left: 3px; margin: 0;" />
    </div>
	<div class="itemLateral" style="padding: 5px;">{$LANG.hp_categorias_Label_Restricao}</div>
	<div class="item" style="padding: 0; padding-top: 6px;">
		<input id="categoriaRestrita" type="checkbox" name="categoriaRestrita" {if $categoriaRestrita == '1'}checked{/if} 
			onClick="javascript: document.getElementById('restricaoCategoria').disabled = !(document.getElementById('categoriaRestrita').checked);" style="margin: 0;" />
		<label for="categoriaRestrita">{$LANG.hp_categorias_CategoriaRestrita}</label>
	</div>
	<div class="itemLateral" style="padding: 5px;">{$LANG.hp_categorias_RestricaoCategoria}</div>
	<div class="item" style="padding: 0;">
		<input type="text" class="FormExtra" size=30 name="restricaoCategoria" id="restricaoCategoria" placeholder="{$LANG.hp_categorias_Placeholder_RestricaoCategoria}" value="{$restricaoCategoria}" tabindex="1" style="padding: 0; padding-left: 3px; margin: 0;" />
  		<script type="text/javascript">document.getElementById('restricaoCategoria').disabled = !(document.getElementById('categoriaRestrita').checked);</script> 
	</div>
        <div class=barra>
		<input type="submit" name="go" id="go" value="{$LANG.gravar}" class="submit" onclick="doAction('{$LANG.gravar}')"/>
{if !$criarCategoria}
		<input type="submit" name="go" id="go" value="{$LANG.excluir}" class="submit" onclick="doAction('{$LANG.excluir}')"/>
		<input type="submit" name="go" id="go" value="{$LANG.novaCategoria}" class="submit" onclick="doAction('{$LANG.novaCategoria}')"/>
{/if}
		<input type="submit" name="go" id="go" value="{$LANG.cancelar}" class="submit" onclick="doAction('{$LANG.cancelar}')"/> 
	</div>
</form>
{if !$criarCategoria}
<div class="subTitulo">{$LANG.grupos}</div>
<div class="tituloColuna">{$LANG.hp_grupos_DescricaoGrupo}</div>
<div class="tituloColuna">{$LANG.subir}</div>
<div class="tituloColuna">{$LANG.descer}</div>
<div class="tituloColuna">{$LANG.excluir}</div>
<div class="tituloColuna">{$LANG.tipoGrupo}</div>
<div class="tituloColuna">{$LANG.grupoRestrito}</div>
<div id="grupos_div">
{include file="admin/grupos_div.tpl"}
</div>
{/if}

{include file="page_footer.tpl"}
