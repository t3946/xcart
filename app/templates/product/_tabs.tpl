<section class="info_tabs">
    <ul class="tabs" data-responsive-accordion-tabs="tabs small-accordion large-tabs" data-allow-all-closed="true" id="product_tabs">
        <li class="tabs-title is-active">
          <a href="#description" aria-selected="true">Description</a>
        </li>

        <li class="tabs-title">
          <a href="#brand" aria-selected="false">Brand</a>
        </li>

        <li class="tabs-title">
          <a href="#shipping" aria-selected="false">Shipping</a>
        </li>

        {foreach $tabs as $tab}
            {if $tab.code == '__shipping__'}{continue}{/if}

            <li class="tabs-title">
              <a href="#{$tab.code}" aria-selected="false">{$tab.name}</a>
            </li>
        {/foreach}

        <li class="tabs-title">
          <a href="#reviews" aria-selected="false">Product reviews</a>
        </li>

        <li class="tabs-title">
          <a href="#questions" aria-selected="false">Product questions</a>
        </li>
    </ul>

    <div class="tabs-content" data-tabs-content="product_tabs">

        <div class="tabs-panel is-active" id="description">
            {include 'product/tabs/_description.tpl' model=$model}
        </div>

        <div class="tabs-panel" id="brand">
            {include 'product/tabs/_brand.tpl' model=$model}
        </div>

        <div class="tabs-panel" id="shipping">
            {include 'product/tabs/_shipping.tpl' model=$model tab=$tabs['__shipping__']!:null}
        </div>

        {foreach $tabs as $tab}
            {if $tab.code == '__shipping__'}{continue}{/if}

            <div class="tabs-panel tab-{$tab.code}" id="{$tab.code}">
                {raw $tab.content}
            </div>
        {/foreach}

        <div class="tabs-panel" id="reviews">
            {include 'product/tabs/_reviews.tpl' model=$model}
        </div>

        <div class="tabs-panel" id="questions">
            {include 'product/tabs/_questions.tpl' model=$model}
        </div>
    </div>

</section>