/*****
 * {$tema->nome}
 *
 * {$tema->comentario}
*****/
@import url('{$includePATH}estilos/{$fontFamily|lower|replace:" ":"-"}.css');

:root {
{section name=rv loop=$rootvars}
{if $rootvars[rv].tipo == 'color'}
    --cor-{$rootvars[rv].rootvar}: {$rootvars[rv].valor};
{elseif $rootvars[rv].tipo = 'font'}
    --font-{$rootvars[rv].rootvar}: {$rootvars[rv].valor};
{/if}
{/section}
}
