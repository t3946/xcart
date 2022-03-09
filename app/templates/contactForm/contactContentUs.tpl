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
                    <div><span class="title">Local</span><wbr>{$config.local_phone}</div>
                {/if}
                {if $config.cidev_top_header_code}
                    <div><span class="title">Toll&nbsp;free</span><wbr>{$config.cidev_top_header_code}</div>
                {/if}
            </div>
        </div>
        <div class="tabs-panel" id="Fax">
            <div class="tab-content">
                {if $config.fax_number}
                    <span>{$config.fax_number}</span>
                {/if}
            </div>
        </div>
        <div class="tabs-panel" id="Mail">
            <div class="row">
                <div class="col-12 col-md-6">
                    <strong>USA Address</strong><br/>
                    S3 Stores, Inc.<br/>
                    2885 Sanford Ave SW #12717<br/>
                    Grandville, MI 49418<br/>
                    USA
                </div>
                <div class="col-12 col-md-6">
                    <strong>Canadian Address</strong><br/>
                    S3 Stores, Inc.<br/>
                    27 Joseph St.<br/>
                    Chatham, Ontario N7L 3G5<br/>
                    Canada
                </div>
            </div>
        </div>
    </div>
</div>