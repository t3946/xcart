<div class="row w1280">
    <div class="columns small-12">
        <div class="flash-messages-block">
            <div class="flash-list"></div>
        </div>
    </div>
</div>

{if $messages}
{add_asset_block type="js"}
    <script>
        window['flashStack'] = [];

        {foreach $messages as $item}
        window['flashStack'].push({ 'message': "{$item['message']|escape}", 'type': "{$item['type']|escape}" });
        {/foreach}
    </script>
{/add_asset_block}
{/if}