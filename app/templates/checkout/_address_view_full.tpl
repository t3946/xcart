<h2 class="title">{$header}</h2>

<div class="row full-name">
    <div class="columns info-title small-6">{t 'Full name:' dict='order'}</div>
    <div class="columns info-text">{$info['firstname']}</div>
</div>
<div class="row company">
    <div class="columns info-title small-6">{t 'Company:' dict='order'}</div>
    <div class="columns info-text">{$info['company']}</div>
</div>
<div class="row address">
    <div class="columns info-title small-6">{t 'Address:' dict='order'}</div>
    <div class="columns info-text">{$info['address'][0]} {if $info['address'][1]}<br/>{$info['address'][1]}{/if}</div>
</div>
<div class="row city">
    <div class="columns info-title small-6">{t 'City:' dict='order'}</div>
    <div class="columns info-text">{$info['city']}</div>
</div>
<div class="row state">
    <div class="columns info-title small-6">{t 'State/Province:' dict='order'}</div>
    <div class="columns info-text">{$info['state']}</div>
</div>
<div class="row country">
    <div class="columns info-title small-6">{t 'Country:' dict='order'}</div>
    <div class="columns info-text">{$info['country']}</div>
</div>
<div class="row zip">
    <div class="columns info-title small-6">{t 'Zip/Postal code:' dict='order'}</div>
    <div class="columns info-text">{$info['zipcode']}</div>
</div>

<div class="row align-center button-row">
    <div class="columns small-12 text-left">
        <a href="{url $uri}?modify=1#billing-address" class="button yellow-white waves waves-orange waves-effect small yellow-border">{t 'Modify' dict='order'}</a>
    </div>
</div>

