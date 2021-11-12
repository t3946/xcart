<h2 class="title">{$header}</h2>

<div class="row full-name">
    <div class="columns info-title small-6">{t 'Full name:' }</div>
    <div class="columns info-text">{$info['firstname']}</div>
</div>
<div class="row company">
    <div class="columns info-title small-6">{t 'Company:' }</div>
    <div class="columns info-text">{$info['company']}</div>
</div>
<div class="row address">
    <div class="columns info-title small-6">{t 'Address:' }</div>
    <div class="columns info-text">{$info['address'][0]} {if $info['address'][1]}<br/>{$info['address'][1]}{/if}</div>
</div>
<div class="row city">
    <div class="columns info-title small-6">{t 'City:' }</div>
    <div class="columns info-text">{$info['city']}</div>
</div>
<div class="row state">
    <div class="columns info-title small-6">{t 'State/Province:' }</div>
    <div class="columns info-text">{$info['state']}</div>
</div>
<div class="row country">
    <div class="columns info-title small-6">{t 'Country:' }</div>
    <div class="columns info-text">{$info['country']->countryNameBySite()}</div>
</div>
<div class="row zip">
    <div class="columns info-title small-6">{t 'Zip/Postal code:' }</div>
    <div class="columns info-text">{$info['zipcode']}</div>
</div>

<div class="row align-center button-row">
    <div class="columns small-12 text-left">
        <a href="{url $uri}?modify=1#billing-address" class="button yellow-white waves waves-orange waves-effect small yellow-border">{t 'Modify' }</a>
    </div>
</div>

