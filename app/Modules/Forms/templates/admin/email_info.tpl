{extends $.request->isAjax ? "ajax.tpl" : "admin/base.tpl"}

{block 'heading'}
    <h1>
        {$object->getField('subject')->getValue()|escape}

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
                {$object->getTo()|escape}
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
        {if $object->getField('attachments')->getValue()->count()}
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
        {/if}
        <tr>
            <td colspan="2">
                <hr/>
               <span><a href="#"><i class="fa fa-reply"></i><span style="margin-left: 5px">Reply</span></a></span>
                <span style="margin-left: 5px">
                   <span>Template</span>
                   <select>
                   </select>
               </span>
                <span style="margin-left: 10px"><a href="#"><i class="fa fa-angle-double-right"></i><span style="margin-left: 5px" >Forward</span></a></span>
                <span style="margin-left: 10px"><a href="#"><i class="fa fa-remove"></i><span style="margin-left: 5px">Delete</span></a></span>
                <hr/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <iframe id="body_5" srcdoc="{$object->getField('body')->getValue()|htmlentities}"
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
