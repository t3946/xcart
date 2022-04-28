<h2 class="title">{$header}</h2>

<div class="row full-name">
    <div class="info-title col-6">{t 'Full name:' }</div>
    <div class="col info-text">{$info['firstname']}</div>
</div>
<div class="row company">
    <div class="info-title col-6">{t 'Company:' }</div>
    <div class="col info-text">{$info['company']}</div>
</div>
<div class="row">
    <div class="info-title col-6">{t 'Address:' }</div>
    <div class="col info-text">{$info['address'][0]} {if $info['address'][1]}<br/>{$info['address'][1]}{/if}</div>
</div>
<div class="row city">
    <div class="info-title col-6">{t 'City:' }</div>
    <div class="col info-text">{$info['city']}</div>
</div>
<div class="row state">
    <div class="info-title col-6">{t 'State/Province:' }</div>
    <div class="col info-text">{$info['state']}</div>
</div>
<div class="row country">
    <div class="info-title col-6">{t 'Country:' }</div>
    <div class="col info-text">{if $info['country']}{$info['country']->countryNameBySite()}{/if}</div>
</div>
<div class="row zip">
    <div class=" info-title col-6">{t 'Zip/Postal code:' }</div>
    <div class="col info-text">{$info['zipcode']}</div>
</div>

<div class="row align-center button-row">
    <div class=" col-12 text-left">
        <a href="{url $uri}?modify=1#billing-address" class="button yellow-white waves waves-orange waves-effect col yellow-border text-decoration-none small">{t 'Modify' }</a>
    </div>
</div>

