{add $hide=false}
{add $hide_link=false}


<div class="slider-block {$hide ? 'hide' : ''} {is_array($classes) ?( ' '|implode:$classes) : $classes}">
    <div class="title_container">
        <div class="title-section">
            {$title}

            {if !$hide_link}
                <a href="{$link}" class="link">{t 'View all'}</a>
            {/if}
        </div>
    </div>
    <div class="slider-data" data-url="{$data_link ?: $link}"></div>
</div>