/*****
 * {$tema->nome}
 *
 * {$tema->comentario}
*****/

:root {
{section name=rv loop=$rootvars}
    --cor-{$rootvars[rv].rootvar}: {$rootvars[rv].cor};
{/section}
}
