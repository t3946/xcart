<table class="dx_form" cellpadding="3" cellspacing="1" width="100%">
    {var $fieldsets = $form->getFieldsets()}
    {if $fieldsets}
        {foreach $fieldsets as $name => $fieldsNames}
            {if $name}
                <tr>
                    <td colspan="3"><br>
                        <table class="SubHeader" cellspacing="0">
                            <tbody>
                            <tr>
                                <td class="Green2">{$name}</td>
                            </tr>
                            <tr>
                                <td class="SubHeaderLine">
                                    <img src="/skin1_kolin/images/spacer.gif" class="Spc"><br/></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            {/if}
            {foreach $fieldsNames as $fieldName}
                {var $field = $form->getField($fieldName)}
                {raw $field->render()}
            {/foreach}
            {if !$name}
            <tr>
                <td colspan="3"><hr></td>
            </tr>
            {/if}
        {/foreach}
    {else}
        {foreach $fields as $field}
            {set $f = $form->getField($field)}
            {$f->render()}
        {/foreach}
    {/if}
</table>
<script>
    $(function () {
        let t= $('.tooltip').tooltip({
            position: {
                using: function (position, feedback) {
                    $(this).css(position);
                    $("<div>")
                        .addClass("tooltip__s3")
                        .appendTo(this);
                }
            },
            content: function(){
                return $(this).attr('title');
            },
            open: function (event, ui) {
                ui.tooltip.css("max-width", "400px");
            }
        });
    });
</script>
