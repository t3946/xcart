<div class="smarty-admin-block {if $class}{$class}{/if}">
    {if $is_show_title !== false && ($name || $right)}
    <div class="title-block">
        <span class="title">
            {$name}
        </span>
        <span class="title right">
            {$right}
        </span>
    </div>
    {/if}
    <div class="white-back content-block">
        {raw $html}
    </div>
</div>

