{extends "_parts/_footer.tpl"}
{block 'mobile-socials'}
{/block}
{block 'footer-logo'}
    <div class="columns large-4 medium-6 small-12">
        <div>
            <div class="footer-info-block-title">{t 'Telephone Customer Service'}</div>
            <ul class="no-bullet menu-list">
                <li class="footer-info-block-item">{$site->customer_service_working_time}</li>
                <li class="footer-info-block-item">
                    {if $site->cidev_top_header_code}
                        <span class="footer-phone">{$site->cidev_top_header_code}</span>
                    {/if}
                </li>
                <li class="footer-info-block-item">
                    {if $site->local_phone}
                        <span class="footer-phone">{$site->local_phone}</span>
                    {/if}
                </li>
                <li class="footer-info-block-item">
                    {if $site->fax_number}
                        {$site->fax_number}
                    {/if}
                </li>
            </ul>
        </div>
    </div>
{/block}
{block 'support'}
{/block}
{block 'socials'}
    <div class="footer-schedule">
        <img alt="{t 'Web order 24/7'}" class="footer-schedule-icon lazy-img"
             data-src="{assets 'images/icons/footer/web_order.svg'}">
        <div class="content">
            <div class="footer-schedule-title">{t 'Web Orders'}</div>
            <div class="footer-schedule-content">{t '24 hours a day'}</div>
            <div class="footer-schedule-content">{t '7 days a week'}</div>
        </div>
    </div>
{/block}
{block 'online-orders'}
    {foreach $addresses as $address_model }
        <div>
            <div class="footer-info-block-title">{$address_model.address.name}</div>
            <ul class="no-bullet menu-list">
                <li class="footer-info-block-item">{$address_model.address.company}</li>
                <li class="footer-info-block-item">{$address_model.address.address}</li>
                <li class="footer-info-block-item">{$address_model.address.address_state}</li>
                <li class="footer-info-block-item">{$address_model.address.country}</li>
            </ul>
        </div>
    {/foreach}
{/block}
{block 'addresses'}
{/block}