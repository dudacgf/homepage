<div class="fortune">
<span>{$fortune.textoFortune}</span>
{if isset($fortune.autorFortune) and $fortune.autorFortune != ''}
<span style="font-size: smaller; font-weight: 600;"><br />-- {$fortune.autorFortune}</span>
{/if}
</div>
