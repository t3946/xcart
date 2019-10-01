{extends "cart/base.tpl"}

{block 'content'}
    <h1>{t('Cart')}</h1>

    <table>
        <thead>
            <tr>
                <th>{t('Name')}</th>
                <th>{t('Quantity')}</th>
                <th>{t('Attributes')}</th>
                <th>{t('Price')}</th>
                <th>{t('Actions')}</th>
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
                    <a href="{url 'cart:delete' key=$key}" title="{t('Delete')}">{t('Delete')}</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>

    <p>{t('Total price')}: {$total}</p>
{/block}