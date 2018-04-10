<div class="slide slide_cover_1">
    {if $slide.link}
    <a href="">
    {else}
    <div>
    {/if}

        <div class="slide-data" data-background="{$slide.image}">
            <h3 class="caption">{$slide.title}</h3>
            <p class="description">{$slide.description}</p>
        </div>

    {if $slide.link}
    </a>
    {else}
    </div>
    {/if}
</div>