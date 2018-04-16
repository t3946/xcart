{add $hidden=false}

<div class="slider-block {$hidden ? 'hide' : ''} {is_array($classes) ?( $classes|implode:' ') : $classes}">
    <div class="title_container">
        <div class="title-section">
            {$title}

            <a href="{$link}" class="link">View all</a>
        </div>
    </div>
    <div class="slider-data" data-url="{$data_link ?: $link}"></div>
</div>