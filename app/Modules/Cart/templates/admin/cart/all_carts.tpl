{extends "base/admin.tpl"}
{block "heading"}
    <h1>{$page_title}</h1>
    <style type="text/css">
        .radio-container{
            display: inline-block;
        }
    </style>
{/block}
{block "content"}
    {smarty_admin_block name='Shopping carts'}
    <script>

        function MoreInfo(id, id2) {
            var string = 'tr#' + id;
            if( (document.querySelector(string)).hasAttribute("hidden") ) {
                ShowInfo(id, id2);
            } else {
                HideInfo(id, id2);
            }
        }

        function ShowInfo(id, id2) {
            document.getElementById(id).removeAttribute('hidden');
            document.getElementById(id2).innerHTML = 'Click me to close';
        }
        function HideInfo(id, id2) {
            document.getElementById(id).setAttribute("hidden", "hidden");
            document.getElementById(id2).innerHTML = 'Click me for more information';
        }
    </script>

    <form method="get" action="{url 'admin_cart:show'}" class="search-form">

        <fieldset class="collapsible expanded " rel="0">
            <legend>Filter</legend>
            {$form->render($form->getTemplateFromType('ul'))}
            <input type="submit" value="Find">&emsp;
            <a href="{url 'admin_cart:show'}">Clean filter</a>
        </fieldset>
    </form>

    <table cellpadding="14" cellspacing="0" style="text-align: center" border="3">
        <thead>
            <tr>
                <th style="width: 750px;">Cart ID</th>
                <th>Check</th>
            </tr>
        </thead>
        <tbody>
        {*{foreach $cart->getItemsGroupedBy() as $gg => $g}*}
            {*{$cart->getCartNumber()|var_dump}*}
        {*{/foreach}*}

        {foreach $pager->paginate() as $model index=$index}

            <tr>
                <td>ID: {$model->id}</td>
                <td><p id="check_me_{$index}" onclick='MoreInfo("information_{$index}", this.id)'">Click me for more information</p></td>
            </tr>
            <tr id="information_{$index}" hidden>
                <td>
                    <hr>
                    {foreach $model->data['cart'] as $p}
                        <img src="{$p->_object->getThumbnail()}"><br>
                        SKU: {$p->_object->productcode}<br>
                        Name: {$p->_object->product}<br>
                        Price: {$p->_object->getPrice()}<br>
                        Quantity: {$p->_quantity}<br>
                        Total price: {$p->_price}<br>
                        <a href="{$p->_object->getAbsoluteUrl()}" target="_blank">Product url</a>
                        <hr>

                    {/foreach}

                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    {/smarty_admin_block}

    {$pager}
{/block}