<form name="salvarEstilo" id="salvarEstilo" action="javascript: salvarEstilo()">
    <div class="subTitulo">{$tituloPaginaAlternativo}{$tituloTabelaAlternativo}</div><p />
    <div class="itemLateral">{$LANG.hp_estilos_nomeEstilo}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 id="nomeEstilo" placeholder="{$LANG.hp_estilos_Placeholder_nomeEstilo}" tabindex="1" /></div>
    <div class="itemLateral">{$LANG.hp_estilos_comentarioEstilo}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 id="comentarioEstilo" placeholder="{$LANG.hp_estilos_Placeholder_comentarioEstilo}" tabindex="2" /></div>
    <div class="itemLateral" colspan="10" style="width: 99%; margin-top: 5px; Text-Align: center;">
    <input type="submit" class="submit" name="go" value="{$LANG.gravar}" tabindex="3" /> :: 
    <input type="button" class="submit" name="go" value="{$LANG.cancelar}" onclick="javascript: ocultarFormDiv();" tabindex="4" />
    </div>
</form>
