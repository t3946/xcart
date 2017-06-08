{if $values|count > 0}
    <ul class="no-bullet short">
            {set $citems = 0}

            {foreach $values as $val index=$index}
                {if $citems >= 7}{break}{/if}
                {if $val.checked}
                <li>
                    {include 'catalog/parts/filters/_list_item.tpl' prefix="s"}
                </li>
                {set $citems = $citems+1}
                {/if}
            {/foreach}

            {foreach $values as $val index=$index}
                {if $citems >= 7}{break}{/if}
                {if !$val.checked}
                <li>
                    {include 'catalog/parts/filters/_list_item.tpl' prefix="s"}
                </li>

                {set $citems = $citems+1}
                {/if}
            {/foreach}
    </ul>
    <a href="#{$key}-f" class="mmodal">
        Show more
    </a>
    <div id="{$key}-f" class="full filter">
        <ul class="no-bullet">
            {foreach $values as $val}
                <li>
                    {include 'catalog/parts/filters/_list_item.tpl' prefix="f"}
                </li>
            {/foreach}
        </ul>
    </div>
{/if}