<section class="info_tabs">
    <ul class="tabs" data-responsive-accordion-tabs="tabs small-accordion large-tabs" data-allow-all-closed="true" data-multi-expand="true" id="product_tabs">
        <li class="tabs-title is-active">
          <a href="#description" aria-selected="true">Description</a>
        </li>

        {*<li class="tabs-title">*}
          {*<a href="#brand" aria-selected="false">Brand</a>*}
        {*</li>*}

        <li class="tabs-title">
          <a href="#shipping" aria-selected="false">Shipping</a>
        </li>

        {foreach $tabs as $tab}
            {if $tab.code == '__shipping__'}{continue}{/if}

            <li class="tabs-title">
              <a href="#{$tab.code}" aria-selected="false">{$tab.name}</a>
            </li>
        {/foreach}

        {*<li class="tabs-title">*}
          {*<a href="#reviews" aria-selected="false">Product reviews</a>*}
        {*</li>*}

        <li class="tabs-title">
          <a href="#questions" aria-selected="false">Product questions</a>
        </li>
    </ul>

    <div class="tabs-content" data-tabs-content="product_tabs">

        <div class="tabs-panel is-active" id="description">
            <div class="tab-description tab-content">
                {include 'product/tabs/_description.tpl' model=$model}
            </div>
        </div>

        {*<div class="tabs-panel " id="brand">*}
            {*<div class="tab-brand tab-content">*}
                {*{include 'product/tabs/_brand.tpl' model=$model}*}
            {*</div>*}
        {*</div>*}

        <div class="tabs-panel" id="shipping">
            <div class="tab-shipping tab-content">
                {include 'product/tabs/_shipping.tpl' model=$model tab=$tabs['__shipping__']!:null}
            </div>
        </div>

        {foreach $tabs as $tab}
            {if $tab.code == '__shipping__'}{continue}{/if}

            <div class="tabs-panel tab-{$tab.code}" id="{$tab.code}">
                <div class="tab-s3 tab-content">
                    <h2>{$tab.name}</h2>
                    <div class="content">
                        {raw $tab.content}
                    </div>
                </div>
            </div>
        {/foreach}

        {*<div class="tabs-panel" id="reviews">*}
            {*<div class="tab-content tab-reviews">*}
                {*{include 'product/tabs/_reviews.tpl' model=$model}*}
            {*</div>*}
        {*</div>*}



        <div class="tabs-panel" id="questions" data-productid="{$model->productid}">
            <div class="tab-content tab-questions">
                {include 'product/tabs/_wait.tpl' model=$model}
            </div>
        </div>
    </div>

</section>