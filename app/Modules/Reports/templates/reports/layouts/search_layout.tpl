{extends 'dashboard/layouts/search_layout.tpl'}

{block 'content'}

    {smarty_admin_block name='Order reports'}
        {include 'reports/admin/_reports.tpl'}

        <form action="{url 'reports:view'}" method="GET" id="report_form" target="_blank">
        {include 'reports/_report_fields.tpl'}
        <fieldset>

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
        $('#report_form').submit(function(e){
            var submit_form =  $(this).closest('form');
            $("input.hidden_groups", submit_form).remove();
            var containers = $('.shapeshift .shapeshift-container.for-save');
            containers.each(function(){
                var cur_container = $(this);
                $(this).find('> div').each(function () {
                    var input = $("<input>")
                        .attr("type", "hidden")
                        .addClass("hidden_groups")
                        .attr("name", "search[report]["+cur_container.attr('data-param-name')+"]["+$(this).attr('data-index')+"]").val($(this).attr('data-model'));
                    submit_form.append($(input));
                });
            });
        });
    })();
</script>

{/block}


