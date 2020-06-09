{extends $.request->isAjax ? "ajax.tpl" : "admin/base.tpl"}

{block 'heading'}
    <h1>
        {$.t('Information about')}
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
    <table class="object-info" style="background-color: #f4f4f4;">
        <tbody>
        <tr>
            <td class="first">
                {$object->getField('date')->getVerboseName()}
            </td>
            <td>
                {$object->getField('date')->getValue()->format('Y-m-d H:i')}
            </td>
        </tr>
        <tr>
            <td class="first">
                {$object->getField('from_address')->getVerboseName()}
            </td>
            <td>
                {$object->getField('from_address')->getValue()|escape}
            </td>
        </tr>
        <tr>
            <td class="first">
                {$object->getField('to_address')->getVerboseName()}
            </td>
            <td>
                {$object->getField('to_address')->getValue()|escape}
            </td>
        </tr>
        <tr>
            <td class="first">
                {$object->getField('subject')->getVerboseName()}
            </td>
            <td>
                {$object->getField('subject')->getValue()|escape}
            </td>
        </tr>
        <tr>
            <td class="first">
                {$object->getField('attachments')->getVerboseName()}
            </td>
            <td>
                <table>
                    {foreach $object->getField('attachments')->getValue() as $model}
                        <tr>
                            <td>
                                <a target="_blank" href="/{$model->attachment}">{$model->filename}</a>
                            </td>
                        </tr>
                    {/foreach}
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr/>
            </td>
        </tr>
        <tr>
            <td class="first">
            </td>
            <td>
                <iframe id="body_5" srcdoc='{$object->getField('body')->getValue()|escape}'
                        frameborder="0"
                        sandbox="allow-same-origin allow-popups"
                        onload="this.height = (this.contentWindow.document.body.scrollHeight * 1.1) + 'px'"
                ></iframe>

            </td>
        </tr>
        </tbody>
    </table>
{/block}

</div>
