<div class="VertMenuBorder">
    <div class="VertMenuTitle">
        {if $link_href}
        <a href="{$link_href}" {if $id_expand ne ""}onclick="javascript: $('#{$id_expand}').toggle();"{/if}>{/if}
            <font class="VertMenuTitle">
                {$menu_title}
            </font>
            {if $link_href}
        </a>
        {/if}
        {if $id_expand ne ""}
            <img src="{$ImagesDir}/br_down.png" alt="" onclick="javascript: $('#{$id_expand}').toggle();" />
        {/if}
    </div>
    <div class="VertMenuBox">
        {$menu_content}
    </div>
</div>
