<div class="smarty-admin-block {if $class}{$class}{/if}">
    <div class="title-block">
        <div class="row">
            <div class="large-{if $title_size}{$title_size}{else}4{/if}">
                <span class="title">
                    {$name}
                </span>
            </div>
        </div>
    </div>
    <div class="white-back orange-border content-block">

        <div class="row">
            <div class="columns large-12">
                {raw $html}
            </div>
        </div>
    </div>
</div>

