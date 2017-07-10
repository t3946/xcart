{extends "cart/base.tpl"}

{block 'content'}
<section class="cart">
    <div class="row">
        <div class="columns large-12">
            <h1>{t 'Cart' dict='cart'}</h1>

            <table>
                <thead>
                    <tr>
                        <th>{t 'Name'  dict='cart'}</th>
                        <th>{t 'Quantity'  dict='cart'}</th>
                        <th>{t 'Attributes'  dict='cart'}</th>
                        <th>{t 'Price'  dict='cart'}</th>
                        <th>{t 'Actions'  dict='cart'}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $items as $key=>$position}
                        <tr>
                        <td>
                            {$position->object}
                        </td>
                        <td>
                            <a href="{url 'cart:quantity_decrease' key=$key}">-</a>
                            <a href="{url 'cart:quantity' key=$key quantity=2}">{$position->quantity}</a>
                            <a href="{url 'cart:quantity_increase' key=$key}">+</a>
                        </td>
                        <td>
                            {foreach $position->data as $name => $value}
                                <p>{$name}: {$value}</p>
                            {/foreach}
                        </td>
                        <td>
                            ${$position->getPrice()}
                        </td>
                        <td>
                            <a href="{url 'cart:delete' key=$key}" title="{t 'Delete' dict='cart'}">{t 'Delete' dict='cart'}</a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>

            <p>{t "Total price" dict='cart'}: {$total}</p>
        </div>
    </div>
</section>
{/block}