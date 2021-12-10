<div class="tabs-content-container">
    <div class="tabs-content" data-tabs-content="contact-us-tabs">
        <div class="tabs-panel is-active" id="Email">
            <div class="tab-content">
                <span class="title">{t 'Email'}</span><a href="mailto:{$config.newsletter_email}">{$config.newsletter_email}</a>
            </div>
        </div>
        <div class="tabs-panel" id="Phone">
            <div class="tab-content">
                {if $config.local_phone}
                    <div><span class="title">{t 'Local'}</span><wbr>{$config.local_phone}</div>
                {/if}
                {if $config.cidev_top_header_code}
                    <div><span class="title">{t 'Toll free'}</span><wbr>{$config.cidev_top_header_code}</div>
                {/if}
            </div>
        </div>
        <div class="tabs-panel" id="Mail">
{*            <div class="row">
                <div class="column small-12 medium-6">*}
                    <strong>Адрес России</strong><br/>
                    ООО Компалг<br/>
                    г. Киров, ул. Азина, д. 48, кв. 4<br/>
                    610027, Кировская обл.<br/>
                    Россия
{*                </div>
            </div>*}
        </div>
    </div>
</div>