<h2 class="title">{$header}</h2>

<div class="row full-name">
    <div class="columns small-4">{t 'Full name:' dict='order'}</div>
    <div class="columns">{$info['firstname']}</div>
</div>
<div class="row company">
    <div class="columns small-4">{t 'Company:' dict='order'}</div>
    <div class="columns">{$info['company']}</div>
</div>
<div class="row address">
    <div class="columns small-4">{t 'Address:' dict='order'}</div>
    <div class="columns">{$info['address'][0]} {if $info['address'][1]}<br/>{$info['address'][1]}{/if}</div>
</div>
<div class="row city">
    <div class="columns small-4">{t 'City:' dict='order'}</div>
    <div class="columns">{$info['city']}</div>
</div>
<div class="row state">
    <div class="columns small-4">{t 'State/Province:' dict='order'}</div>
    <div class="columns">{$info['state']}</div>
</div>
<div class="row country">
    <div class="columns small-4">{t 'Country:' dict='order'}</div>
    <div class="columns">{$info['country']}</div>
</div>
<div class="row zip">
    <div class="columns small-4">{t 'Zip/Postal code:' dict='order'}</div>
    <div class="columns">{$info['zipcode']}</div>
</div>

<div class="row align-center">
    <div class="columns small-12">
        <a href="{url $uri}" class="button yellow-white waves waves-orange waves-effect">{t 'Modify' dict='order'}</a>
    </div>
</div>