<label for="{$id}" {raw $html}>{raw $label}</label>
    {if (strpos($html, 'required') === false)}
        <span class="comment">(optional)</span>
    {/if}