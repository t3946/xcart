{add $hide=false}
{add $hide_link=false}

<div class="slider-block {$hide ? 'hide' : ''} {is_array($classes) ?( $classes|implode:' ') : $classes}">
    <div class="title_container">
        <div class="title-section">
            {$title}

            {if !$hide_link}
                <a href="{$link}" class="link">View all</a>
            {/if}
        </div>
    </div>
    <div class="slider-data" data-url="{$data_link ?: $link}"></div>
    <div class="scrollbar">
        <div class="handle"></div>
    </div>
</div>