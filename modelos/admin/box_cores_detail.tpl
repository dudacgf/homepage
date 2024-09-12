<div class="tab">
    <input type="radio" name="css-tabs" id="tab-{$nomePaleta}" class="tab-switch">
    <label for="tab-{$nomePaleta}" class="tab-label">{if isset($fa_icon)}<i class="{$fa_icon}"></i>{/if}{$nomePaleta}</label>
    <div class="tab-content" style="justify-contents: center; align-items: center;">
        <div style="width: calc({$size}px + 20px); border: 1px solid #c0c0c0; border-radius: 6px; padding: 6px;">
        <div class="contentCor textMiddle" id="boxContent-{$nomePaleta}" style="width: {$size}px; border: 1px solid #c0c0c0; border-radius: 0; margin: 10px;">
        {section name=pc loop=$paresCores}
                <div class="cor" style="background-color: {$paresCores[pc].cor}" onClick="boxCorClick('{$paresCores[pc].nome}', '{$paresCores[pc].cor}','{$paresCores[pc].hspCor}')"></div>
            {/section}
        </div>
        </div>
    </div>
</div>
