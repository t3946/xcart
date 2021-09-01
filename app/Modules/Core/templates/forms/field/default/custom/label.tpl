<label for="{$id}" {raw $html}>{raw $label}</label>
{if (strpos($html, 'required') === false)}
    <span class="{$field->labelCommentClass}">({t 'optional'})</span>
{/if}