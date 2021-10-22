<section class="info_tabs">
    <ul class="tabs" data-responsive-accordion-tabs="tabs small-accordion large-tabs" data-allow-all-closed="true" data-multi-expand="true" id="product_tabs">
        <li class="tabs-title is-active">
          <a href="#description" aria-selected="true">{t 'Description'}</a>
        </li>

        <li class="tabs-title">
          <a href="#shipping" aria-selected="false">{t 'Shipping'}</a>
        </li>
        {add $warehouse = $model->distributor}
        {set $tabs = $warehouse->tabs}
        {foreach $tabs as $tab}
            {if $tab->name === 'Shipping'}{continue}{/if}
            {if $tab->name === 'Return policy'}{continue}{/if}

            <li class="tabs-title">
              <a href="#{$tab->tab_id}" aria-selected="false">{$tab->name}</a>
            </li>
        {/foreach}

        <li class="tabs-title">
            <a href="#return-policy" aria-selected="false">{t 'Return Policy'}</a>
        </li>

        <li class="tabs-title">
          <a href="#questions" aria-selected="false">{t 'Product questions'}</a>
        </li>
    </ul>

    <div class="tabs-content" data-tabs-content="product_tabs">

        <div class="tabs-panel is-active" id="description">
            <div class="tab-description tab-content">
                {include 'product/tabs/_description.tpl' model=$model}
            </div>
        </div>

        <div class="tabs-panel" id="shipping">
            <div class="tab-shipping tab-content">
                {include 'product/tabs/_shipping.tpl' model=$model tab=$tabs->filter(['name' => 'shipping'])!:null}
            </div>
        </div>

        {foreach $tabs as $tab}
            {if $tab->name === 'Shipping'}{continue}{/if}
            {if $tab->name === 'Return policy'}{continue}{/if}

            <div class="tabs-panel tab-{$tab->name}" id="{$tab->tab_id}">
                <div class="tab-s3 tab-content">
                    <div class="h2">{$tab->name}</div>
                    <div class="content">
                        {raw $tab->content|html_entity_decode}
                    </div>
                </div>
            </div>
        {/foreach}

        <div class="tabs-panel" id="return-policy">
            <div class="tab-return-policy tab-content">
                {if $warehouse->d_frontend_return_policy}
                    {$warehouse->d_frontend_return_policy|html_entity_decode}
                {else}
                    {if in_array($site->lang->lang_code, ['ru'])}
                        {$gConfig.frontend_return_policy_ru|html_entity_decode}
                    {else}
                        {$gConfig.frontend_return_policy|html_entity_decode}
                    {/if}
                {/if}
            </div>
        </div>

        <div class="tabs-panel" id="questions" data-productid="{$model->productid}">
            <div class="tab-content tab-questions">
                {include 'product/tabs/_wait.tpl' model=$model}
            </div>
        </div>
    </div>

</section>