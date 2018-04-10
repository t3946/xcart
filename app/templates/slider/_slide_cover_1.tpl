<div class="slide slide_cover_1">
    {if $slide.link}
    <a href="">
    {else}
    <span>
    {/if}

        <div data-background="{$slide.image}">
            <h3 class="caption">{$slide.title}</h3>
            <p class="description">{$slide.description}</p>
        </div>

    {if $slide.link}
    <a href="">
    {else}
    </span>
    {/if}
</div>