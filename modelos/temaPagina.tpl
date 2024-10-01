/*****
 * {$tema->nome}
 *
 * {$tema->comentario}
*****/
@import url('{$includePATH}estilos/{$rootFF|lower|replace:" ":"-"}.css');
{if isset($tituloFF)}
@import url('{$includePATH}estilos/{$tituloFF|lower|replace:" ":"-"}.css');
{/if}

:root {
{section name=rv loop=$rootvars}
{if $rootvars[rv].tipo == 'color'}
    --cor-{$rootvars[rv].rootvar}: {$rootvars[rv].valor};
{elseif $rootvars[rv].tipo = 'font'}
    --font-{$rootvars[rv].rootvar}: {$rootvars[rv].valor};
{/if}
{/section}
}
