<div class="slide slide_cover_1 banner dark">
    {if $slide.link}
    <a href="{$slide.link}"  class="slide-data banner__cover" data-background="{$slide.image}">
    {else}
    <div class="slide-data lazy-bg banner__cover" data-background="{$slide.image}">
    {/if}
        <div class="banner__info slide-info multiline">
            <h3 class="caption multiline">{$slide.title}</h3>
            <p class="description multiline">{$slide.description}</p>
        </div>
    {if $slide.link}
    </a>
    {else}
    </div>
    {/if}
</div>