<p />
<div class="tituloLateral" style="width: 100%; float: none; font-size: 0.9em;">
{if isset($cabecalhoUpload)}
{$cabecalhoUpload}
{else}
File Upload
{/if}
</div>
<form name="upform" enctype="multipart/form-data" action="{$form_action}" method="POST">
	<input type="hidden" name="MAX_FILE_SIZE" value="{$maxfilesize}" />
	<input name="userfile" type="file" size="{$filenamesize}" />
<br />
	<input type="submit" class="submit" value="{if isset($uploadLabel)}{$uploadLabel}{else}Enviar{/if}" />
</form>
<br />

{* vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
