{if $values|count > 0}
    <div class="filter_list">
        <ul class="no-bullet filter short">
            {set $citems = 0}
            {foreach $values as $val index=$index}
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
        {if $values|count > 7}
            <a href="#filter_form" class="show_all short" {if $modal_class? }data-modal-class="{$modal_class}"{/if}>
                Show more
            </a>
        {/if}
        <div id="{$key}-f-{$index}" class="full filter">
            <ul class="no-bullet">
                {foreach $values as $val}
                    <li>
                        {include 'catalog/parts/filters/_list_item.tpl' prefix="f"}
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
{/if}