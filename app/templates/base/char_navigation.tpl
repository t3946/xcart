{set $range = $.php.array_merge(['0-9'],$.php.range('a', 'z'))}

<ul class="char_navigation">
    <li>Alphabetic order:</li>
    {foreach $range as $rr}
        <li {if $rr == $selected}class="selected"{/if}>
            {if $rr != $selected}<a href="{url $url}/{$rr}">{/if}{$rr|upper}{if $rr != $selected}</a>{/if}
        </li>
    {/foreach}
</ul>