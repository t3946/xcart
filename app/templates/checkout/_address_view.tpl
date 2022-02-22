<div class="info_sended_data">
    <h2 class="title default-form-header">{$header}</h2>

    <ul class="address-view">
        <li>{$info['address'][0]}</li>
        {if $info['address'][1]}
            <li>{$info['address'][1]}</li>
        {/if}
        <li>{$info['city']}</li>
        <li>{$info['state']}</li>
        <li>{$info['country']}</li>
        <li>{$info['zipcode']}</li>
    </ul>

    <div class="row align-center">
        <div class=" col-12">
            <a href="{url $uri}" class="button yellow-white waves waves-orange waves-effect col yellow-border text-decoration-none">{t 'Modify' }</a>
        </div>
    </div>
</div>