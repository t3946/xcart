{extends $.request->isAjax ? "ajax.tpl" : "admin/base.tpl"}

{block 'heading'}
    <h1>
        {$.t('Information about', 'admin')}
        {$admin->name}

        {*<a href="{url 'admin:info_print' $moduleName $adminClass $object.pk}"*}
           {*target="_blank"*}
           {*class="window-open ui button tiny basic print-button">*}
            {*{$.t('Print', 'admin')}*}
        {*</a>*}
    </h1>

{/block}

{block 'main_block'}

<div class="admin-page info-page {block 'page_class'}info{/block}">
    <table class="object-info">
        <tbody>
        {foreach $fields as $name => $field index=$index}
            {set $label}
            {if is_array($field) && $field.verboseName}
                {$field.verboseName}

            {elseif (is_string($field))}
                {$object->getField($name)->getVerboseName()}

            {else}
                {if $name == 'id' or $name == 'pk' }
                    {$.t('Number / identifier', 'admin')}
                {else}
                    {$name|ucfirst}
                {/if}
            {/if}
            {/set}


            {set $value = $object->getField($name)->getValue() }
            {set $multiline = false}
            {set $details}
            {if is_array($field)}

                {if $field.choices}
                    {$field.choices[$value]}

                {elseif $field.class == 'Xcart\\App\\Orm\\Fields\\BooleanField' }
                    {if $value }{$.t('Yes', 'admin')}{else}{$.t('No', 'admin')}{/if}

                {elseif $field.class == 'Xcart\\App\\Orm\\Fields\\DateTimeField' or $field.class == 'Xcart\\App\\Orm\\Fields\\DateField' }
                    {$value|humanizeDateTime}

                {elseif $field.class == 'Xcart\\App\\Orm\\Fields\\ForeignField' and method_exists($object[$name], 'getAbsoluteUrl') }
                    <a href="{$object[name]->getAbsoluteUrl()}" target="_blank">
                            {$object[$name]}
                    </a>

                {elseif $field.class == 'Xcart\\App\\Orm\\Fields\\HasToOneField' and method_exists($object[$name], 'getAbsoluteUrl') }
                    <a href="{$object[name]->getAbsoluteUrl()}" target="_blank">
                        {$object[$name]}
                    </a>

                {elseif $field.class == 'Xcart\\App\\Orm\\Fields\\TreeForeignField' and method_exists($object[$name], 'getAbsoluteUrl') }
                    <a href="{$object[$name]->getAbsoluteUrl()}" target="_blank">
                        {$object[$name]}
                    </a>

                {elseif $field.class == 'Xcart\\App\\Orm\\Fields\\HasManyField'}
                    <table>
                        {foreach $value->all() as $model}
                        <tr>
                            <td>
                                {$model}
                            </td>
                        </tr>
                        {/foreach}
                    </table>


                {elseif $field.class == 'Xcart\\App\\Orm\\Fields\\TextField' }
                    <iframe id="{$name}_{$index}" srcdoc='{$value|escape}'
                            frameborder="0"
                            sandbox="allow-same-origin allow-scripts"
                            onload="this.height = this.height + this.contentWindow.document.body.scrollHeight * 1.1;"
                    ></iframe>
                    {set $multiline = true}

                {else}
                    {if $field.class == 'Xcart\\App\\Orm\\Fields\\FileField'}
                        <a href="{$value}">{$value}</a>
                    {else}
                        {$value|escape}
                    {/if}
                {/if}
            {else}
                {$value|escape}
            {/if}
            {/set}

            {if $multiline}
            <tr>
                <td class="first" colspan="2">
                    {$label}
                    <br>

                    {$details}
                </td>
            </tr>
            {else}
                <tr>
                <td class="first">
                    {$label}
                </td>
                <td>
                    {$details}
                </td>
            </tr>
            {/if}
        {/foreach}
        </tbody>
    </table>
{/block}

</div>
