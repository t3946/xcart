{extends 'base/admin.tpl'}
{block 'heading'}
    <h1 class="column large-10" align="center">Customer Care dashboard</h1>
    {if $site && $user}
        <a class="column large-2 button create-order" href="//{$site->domain}/?identify_admin={$user->login}" target="_blank">Create New Cx order</a>
    {/if}
{/block}

{block 'before-content'}
    {autoescape false}
    {orders_test_checkout}
    {/autoescape}
{/block}

{block 'content'}
    <div class="smarty-admin-block">
        <div class="tabs">
            <ul>
                <li>
                    <a href="#my_responsibilities" class="link {if $myModels|count > 0}active{/if}">My responsibilities</a>
                </li>
                <li>
                    <a href="#dashboard" class="link {if $myModels|count == 0}active{/if}">Order dashboard</a>
                </li>
                <li>
                    <a href="#pending_po" class="link">Pending PO entry</a>
                </li>
                <li>
                    <a href="#product_questions" class="link">Product questions</a>
                </li>
                <li>
                    <a href="#cart_retrieval" class="link">Cart retrieval</a>
                </li>
                <li>
                    <a href="#inquiries" class="link">Inquiries</a>
                </li>
            </ul>

            <div class="tabs-content">
                <div class="tab my_dashboard white-back orange-border content-block {if $myModels|count > 0}active{/if}" id="my_responsibilities">
                    {include 'dashboard/_dashboard_my.tpl' models=$myModels my_position=true row_col=['col'=> $row_col.col, 'row' => 25]}
                </div>
                <div class="tab white-back orange-border content-block {if $myModels|count == 0}active{/if}" id="dashboard">
                    <div class="row view_sets">
                        <div class="column text-right">
                            <select class="viewer" name="mode">
                                <option{if $mode} data-loc="{url 'dashboard:index'}#dashboard"{/if} value="0">Standard</option>
                                <option{if $mode===1} selected="selected" {/if} {if $mode > 1} data-loc="{url 'dashboard:assignments'}#dashboard"{/if} value="1">Filter assignments</option>
                                <option{if $mode === 2} selected="selected" {else} data-loc="{url 'dashboard:operators'}#dashboard" {/if} value="2">Operator responsibilities</option>
                            </select>
                        </div>
                    </div>
                    {if $mode < 2}
                        {include 'dashboard/_dashboard_group.tpl' models=$models|get_filtered:null group=null title='Not in group'}

                        {foreach $groups as $group}
                            {include 'dashboard/_dashboard_group.tpl' models=$models|get_filtered:$group->id group=$group->id title=$group}
                        {/foreach}
                    {elseif $mode === 2}
                        {foreach $users as $user}
                            {include 'dashboard/_dashboard_group.tpl' models=$models|get_filtered:null:$user->id group=$user->id title=$user}
                        {/foreach}
                    {/if}

                </div>
                <div id="pending_po">
                    {smarty_admin_block name= 'Pending PO entry'}
                        Here are the places where you can find new POs to enter. Please make sure to login to <strong>s3helpdesk@gmail.com</strong> first. Once PO has been entered, please put Green star on the message to indicate that it has been taken care of.
                        <br>
                        <br>
                        <table>
                            <tbody>
                            <tr>
                                <td><a href="https://mail.google.com/mail/u/0/?tab=wm#label/_Communications%2FFaxage+received"
                                       target="_blank">Faxage-Received</a></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><a href="https://mail.google.com/mail/u/0/?tab=wm#label/_!CustService+Inside+Communications"
                                       target="_blank">CustService-Inside-Communications</a></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><a href="https://mail.google.com/mail/u/0/?tab=wm#label/_Communications%2FMailboxForwarding+Service"
                                       target="_blank">MailboxForwarding-Service</a></td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        <strong>Once PO has been found, it must be uploaded here:<br></strong>
                        <br>
                        <strong><a href="/admin/purchase_orders.php" target="_blank">PO pipeline</a></strong>
                    {/smarty_admin_block}
                </div>
                <div id="product_questions">
                    {smarty_admin_block name= 'Product questions'}
                        <div class="question_products">
                            {include 'dashboard/_product_question.tpl'}
                        </div>
                    {/smarty_admin_block}
                </div>
                <div id="cart_retrieval">
                    {smarty_admin_block name= 'Cart retrieval'}
                        <form action="{url "admin_cart:show"}" method="get">
                            <b>Cart number:</b>
                            <input name="ShoppingCartForm[id]" size="10" value="" id="cart_number" type="number">
                            <input type="submit" value="Search cart"  />
                        </form>
                    {/smarty_admin_block}
                </div>
                <div id="inquiries">
                    {smarty_admin_block name= 'Inquiries' right="<a target='_blank' href='/admin/create_new_inquiry.php'>Create new iquiries</a>"}
                        <table>
                            <tr>
                                <td width="110"><a href="/admin/send_W9_form.php" target="_blank">Send W-9 form</a></td>
                                <td width="30%" valign="top" align="center">
                                {if $inquiries}
                                    <div><b>Inquiry types</b></div>
                                    {foreach $inquiries as $inquiry}
                                        <a target="_blank" href="{$inquiry->getUrl()}">{$inquiry->inquiry_type} ({$inquiry->count()})</a><br />
                                    {/foreach}
                                {/if}
                                </td>
                                <td valign="top" width="20%" align="center">
                                    {if $inquiries_tags}
                                        <div><b>Inquiry tags</b></div>
                                        {foreach $inquiries_tags as $inquiries_tag}
                                            {set $cnt = $inquiries_tag->count()}
                                            {if $cnt}
                                                <a target="_blank" href="inquiries.php?inq_tag_id={$inquiries_tag->inq_tag_id}">{$inquiries_tag->inquiry_attn_tag} ({$inquiries_tag->count()})</a><br />
                                            {/if}
                                        {/foreach}
                                    {/if}
                                </td>
                                <td colspan="3" width="*"></td>
                            </tr>
                        </table>

                    {/smarty_admin_block}
                </div>
            </div>
        </div>
    </div>
{/block}

{block 'js'}
    {parent}
    <script>
        (function () {
            var url_dashboard_update = '{url 'dashboard:index'}';
            var url_dashboard_my_sort = '{url 'dashboard:sort_my_filters'}';

            {ignore}
            $(document).ready(function(){
                $(document).dashboard({
                    ajax: {
                        url: url_dashboard_update
                    }
                });

                $('.dashboard-filters.index a[data-id]').majaxtooltip({
                    onAfterSubmit: function() {
                        this.setContent("<div class='load'></div>")
                    },
                    onAfterSuccess: function() {
                        $.mnotify({
                            title: '"My dashboard" changed',
                            message: 'Refresh the page to display\\hide the elements'
                        });
                    }
                });

                $('.my_dashboard .dashboard-filters ').tablePositions({
                    draggableSelector: '.button, .empty',
                    dropSelector: '.container',

                    onMove: function (el, to) {
                        var def = $.Deferred();
                        $.ajax({
                            type: 'POST',
                            url: url_dashboard_my_sort,
                            data: {
                                position_row: $(to).data('row'),
                                position_column: $(to).data('col'),
                                id: $(el).data('id')
                            },
                            success: function (data) {
                                if (data) {
                                    $.mnotify({
                                        title: 'Position saved',
                                        message: data.message
                                    });

                                    def.resolve(true, data);
                                }
                                def.reject(false);
                            },
                            error: function () {
                                def.reject(false);
                            }
                        });

                        return def.promise();
                    }
                });

                $('.tabs').tabs(
                    {/ignore}
                    {if $myModels|count == 0}
                        {ignore}{active: 1}{/ignore}
                    {/if}
                    {ignore}
                )});
            {/ignore}
        })();
    </script>
{/block}