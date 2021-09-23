<div class="switcher-slider billing-same-shipping-switcher billing__switcher">
    <div class="switcher-slider-border">
        <div class="switcher-slider-label">
            {raw $input}

            {*render with correct styles*}
            {if $value === false}
                <b class="switcher-slider-caption switcher-slider-disable-caption switcher-slider-caption_disabled" style="left: -87px; opacity: 0;">{t 'no'}</b>
                <span class="switcher-slider-ball" style="left: 2px;"></span>
                <b class="switcher-slider-caption switcher-slider-caption_enabled" style="right: 13px; opacity: 1;">{t 'yes'}</b>
                <div class="switcher-slider-background" style="left: -137px;"></div>
                <div class="switcher-slider-shadow"></div>
            {else}
                <b class="switcher-slider-caption switcher-slider-disable-caption switcher-slider-caption_disabled" style="left: 13px; opacity: 1;">{t 'no'}</b>
                <span class="switcher-slider-ball" style="left: 54px;"></span>
                <b class="switcher-slider-caption switcher-slider-caption_enabled" style="right: -37px; opacity: 0;">{t 'yes'}</b>
                <div class="switcher-slider-background" style="left: -5px;"></div>
                <div class="switcher-slider-shadow"></div>
            {/if}
        </div>
    </div>
</div>