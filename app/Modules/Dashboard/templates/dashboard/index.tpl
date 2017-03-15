{extends 'dashboard/layouts/menu_layout.tpl'}
{block 'heading'}
    <h1 align="center">Customer Care dashboard.</h1>

    {autoescape false}
    {orders_test_checkout}
    {/autoescape}
{/block}

{block 'content'}
    <div class="smarty-admin-block">
        <div class="tabs">
            <div class="tabs-title">
                <a href="#my_dashboard" class="link {if $myModels|count > 0}active{/if}">My dashboard</a>
                <a href="#dashboard" class=" link {if $myModels|count == 0}active{/if}">Order dashboard</a>
            </div>

            <div class="tabs-content">
                <div class="tab my_dashboard white-back orange-border content-block {if $myModels|count > 0}active{/if}" id="my_dashboard">
                    {include 'dashboard/_dashboard_my.tpl' models=$myModels my_position=true row_col=['col'=> $row_col.col, 'row' => 25]}
                </div>
                <div class="tab white-back orange-border content-block {if $myModels|count == 0}active{/if}" id="dashboard">
                    {include 'dashboard/_dashboard_group.tpl' models=$models|get_filtered:null group=null title='Not in group'}

                    {foreach $groups as $group}
                        {include 'dashboard/_dashboard_group.tpl' models=$models|get_filtered:$group->id group=$group->id title=$group}
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
    {smarty_admin_block name= 'Pending PO entry dashboard'}
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

    {smarty_admin_block name= 'Product questions dashboard'}
        <div class="question_products">
            {include 'dashboard/_product_question.tpl'}
        </div>
    {/smarty_admin_block}

    {smarty_admin_block name= 'Customer Care Dashboard'}
        <form name="searchform" action="customers_cart.php" method="get">
            <input name="mode" value="search_cart" type="hidden">

            <b>Cart number:</b>
            <input name="cart_number" size="10" value="" id="cart_number" type="text">

            <input type="button" value="Search cart" onclick="javascript: window.open('customers_cart.php?cart_number='+$('#cart_number').val());" />
        </form>
    {/smarty_admin_block}
{/block}

{block 'js'}
    {parent}
    <script>
        $(function () {
            var url_dashboard_update = '{url 'dashboard:index'}';
            var url_dashboard_my_sort = '{url 'dashboard:sort_my_filters'}';

            {ignore}
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
            {/ignore}
        })();
    </script>
{/block}