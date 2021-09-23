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
    <div class="slider-data" data-url="{$data_link ?: $link}">
        <div class="slider-sceleton-wrap">
            <div class="sceleton-slider-card-wrapper">
                <div   class="sceleton" style="width: 100%; max-width: 100%; height: 172px; margin: 0 0 10px 0" ></div>
                <div  class="sceleton" style="width: 100%; max-width: 100%; height: 40px; margin: 0 0 5px 0" ></div>
                <div  class="sceleton" style="width: 100%; max-width: 100%; height: 15px; margin: 0 0 5px 0" ></div>
            </div>
            <div class="sceleton-slider-card-wrapper">
                <div  class="sceleton" style="width: 100%; max-width: 100%; height: 172px; margin: 0 0 10px 0" ></div>
                <div  class="sceleton" style="width: 100%; max-width: 100%; height: 40px; margin: 0 0 5px 0" ></div>
                <div  class="sceleton" style="width: 100%; max-width: 100%; height: 15px; margin: 0 0 5px 0" ></div>
            </div>
            <div class="sceleton-slider-card-wrapper">
                <div  class="sceleton" style="width: 100%; max-width: 100%; height: 172px; margin: 0 0 10px 0" ></div>
                <div  class="sceleton" style="width: 100%; max-width: 100%; height: 40px; margin: 0 0 5px 0" ></div>
                <div  class="sceleton" style="width: 100%; max-width: 100%; height: 15px; margin: 0 0 5px 0" ></div>
            </div>
            <div class="sceleton-slider-card-wrapper">
                <div class="sceleton" style="width: 100%; max-width: 100%; height: 172px; margin: 0 0 10px 0" ></div>
                <div class="sceleton" style="width: 100%; max-width: 100%; height: 40px; margin: 0 0 5px 0" ></div>
                <div class="sceleton" style="width: 100%; max-width: 100%; height: 15px; margin: 0 0 5px 0" ></div>
            </div>
            <div class="sceleton-slider-card-wrapper">
                <div class="sceleton" style="width: 100%; max-width: 100%; height: 172px; margin: 0 0 10px 0" ></div>
                <div class="sceleton" style="width: 100%; max-width: 100%; height: 40px; margin: 0 0 5px 0" ></div>
                <div class="sceleton" style="width: 100%; max-width: 100%; height: 15px; margin: 0 0 5px 0" ></div>
            </div>
            <div class="sceleton-slider-card-wrapper">
                <div class="sceleton" style="width: 100%; max-width: 100%; height: 172px; margin: 0 0 10px 0" ></div>
                <div class="sceleton" style="width: 100%; max-width: 100%; height: 40px; margin: 0 0 5px 0" ></div>
                <div class="sceleton" style="width: 100%; max-width: 100%; height: 15px; margin: 0 0 5px 0" ></div>
            </div>
        </div>
    </div>
</div>