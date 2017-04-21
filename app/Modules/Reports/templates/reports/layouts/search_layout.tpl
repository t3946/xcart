{extends 'dashboard/layouts/search_layout.tpl'}

{block 'content'}

    {smarty_admin_block name='Report options'}
        {include 'reports/admin/_reports.tpl'}

        <form action="{url 'reports:view'}" method="GET" target="_blank">
        {include 'reports/_report_fields.tpl'}
        <fieldset>
            <legend>
                Order search options
            </legend>
        {include 'dashboard/_filter_fields.tpl'}
        </fieldset>
        {include 'reports/layouts/_search_form_block.tpl'}
    </form>
    {/smarty_admin_block}

{/block}

{block 'js'}
{parent}
<script type="text/javascript">
    (function(){
        $('.shapeshift .shapeshift-container').shapeshift({
            colWidth: 200
        }).on("ss-rearranged ss-added ss-removed", function (e, selected) {
            $('> div', $(this)).each(function(i, elem){
                $(elem).attr('data-index', ++i);

            });
        });
        $('#report_edit_form').submit(function(e){
            var submit_form =  $(this).closest('form');
            $('.shapeshift .shapeshift-container.for-save > div').each(function(){
                var input = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "search[report][group_settings]["+$(this).data('index')+"]").val($(this).data('model'));
                submit_form.append($(input));
            });
        });
    })();
</script>

{/block}


