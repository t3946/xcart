{extends "cart/base.tpl"}

{block 'content'}
    <h1>{$.t('Cart', 'cart')}</h1>

    <table>
        <thead>
            <tr>
                <th>{$.t('Name', 'cart')}</th>
                <th>{$.t('Quantity', 'cart')}</th>
                <th>{$.t('Attributes', 'cart')}</th>
                <th>{$.t('Price', 'cart')}</th>
                <th>{$.t('Actions', 'cart')}</th>
            </tr>
        </thead>
        <tbody>
            {foreach $items as $key=>$position}
            <tr>
                <td>
                    {$position->object}
                </td>
                <td>
                    <a href="{url 'cart:quantity:dec' key=$key}">-</a>
                    <a href="{url 'cart:quantity:set' key=$key quantity=2}">{$position->quantity}</a>
                    <a href="{url 'cart:quantity:inc' key=$key}">+</a>
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
                    <a href="{url 'cart:delete' key=$key}" title="{$.t('Delete', 'cart')}">{$.t('Delete', 'cart')}</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>

    <p>{$.t("Total price", 'cart')}: {$total}</p>
{/block}