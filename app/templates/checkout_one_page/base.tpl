{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}


{block 'schema_page_type'}itemtype="http://schema.org/CollectionPage"{/block}
{block 'noindex'}
    <meta name="robots" content="noindex">
{/block}


{block "header"}
    <header class="checkout-hat" itemscope itemtype="http://schema.org/WPHeader">
        <div class="checkout-hat-top-line hidden show-for-large">
            <div class="row">
                <div class="columns large-4 d-flex">
                    <div class="checkout-hat-site-name">{$.getSite->short_name}</div>
                </div>

                <div class="columns large-4"></div>

                <div class="columns large-4 text-align--right">
                    <div class="top-line-online-ordering">
                        <i class="place-order-online-icon common-icon"></i>
                        <span class="top-line-online-ordering-text">24/7 online ordering</span>
                        <i class="lang-icon common-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row checkout-hat-wrapper">
            <div class="columns large-4 medium-6 checkout-hat-logo">
                <a href="/">
                    <img src="{$uri}/static/frontend/dist/images/logos/sites/{$site->code|lower}/logo.svg"
                         alt="{$site->company_name}"
                         class="show-for-large logo-big checkout-hat-logo-image"
                    >

                    <img src="{$uri}/static/frontend/dist/images/logos/sites/{$site->code|lower}/logo-small.svg"
                         alt="{$site->company_name}"
                         class="show-for-small hide-for-large logo-small checkout-hat-logo-image">
                </a>
            </div>

            <div class="columns large-4 hidden show-for-large">
                <div class="secure-checkout">
                    <span class="secure-checkout_text">Secure Checkout</span>
                    <svg width="18" height="24" viewBox="0 0 18 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.78989 9.02599L2.82671 9.01918L2.94537 9.00827H3.29315V8.85006C3.29315 8.40681 3.28633 8.06857 3.27815 7.73033C3.24814 6.40739 3.21814 5.04627 3.53455 3.86244C3.58638 3.66468 3.65321 3.46283 3.73504 3.25961C3.81414 3.05913 3.90552 2.86546 4.00917 2.67997C4.46197 1.86712 5.13572 1.20565 5.92539 0.744666C6.72461 0.276864 7.64794 0.012276 8.58354 0.00136519L8.58764 0H8.62582L8.62992 0.00136519C9.56688 0.012276 10.5066 0.278227 11.3208 0.748757C12.1187 1.20974 12.8019 1.86984 13.2547 2.67997C13.3584 2.86546 13.4498 3.05913 13.5289 3.25961C13.6107 3.46283 13.6762 3.66468 13.7294 3.86244C14.0458 5.04627 14.0144 6.40739 13.9844 7.73033C13.9776 8.06857 13.9694 8.40681 13.9694 8.85006V9.00827H14.4604L14.5791 9.01918L14.6172 9.02599C15.7547 9.21557 17.45 9.49925 17.4009 13.3371C17.4118 14.9942 17.4036 16.4781 17.3954 18.0929L17.3927 18.7721C17.3872 19.7909 17.3149 20.717 16.9699 21.4589C16.5703 22.314 15.872 22.8869 14.6486 23.0205L14.5709 23.0232L8.70356 23.0301L2.8349 23.0232L2.75852 23.0205C1.53378 22.8869 0.835484 22.314 0.437238 21.4589C0.090819 20.717 0.0185345 19.7909 0.0130791 18.7721L0.0103508 18.097C0.00216769 16.4808 -0.00601528 14.9956 0.00625942 13.3371C-0.0442032 9.49925 1.65107 9.21557 2.78989 9.02599ZM8.619 17.5078C7.78705 17.5078 7.11058 16.8327 7.11058 15.9994C7.11058 15.1661 7.78705 14.491 8.619 14.491C9.45232 14.491 10.1288 15.1661 10.1288 15.9994C10.1288 16.8327 9.45232 17.5078 8.619 17.5078ZM4.82749 9.00827H12.4364L12.4392 8.8446C12.4433 8.53091 12.4433 8.21723 12.4433 7.9049L12.4337 6.68971C12.4296 5.93823 12.4214 4.80486 12.2605 4.24023C12.0286 3.4301 11.554 2.7659 10.9307 2.30083C10.2925 1.82484 9.49597 1.55616 8.64219 1.54934V1.5507H8.57127V1.54934C7.72432 1.55616 6.94555 1.82348 6.32091 2.29401C5.70308 2.76044 5.23528 3.42737 5.00342 4.24023C4.84113 4.80486 4.83431 5.93823 4.83021 6.68971L4.82067 7.90354C4.8193 8.21587 4.82067 8.53091 4.82476 8.8446L4.82749 9.00827ZM2.99719 10.4567C2.34936 10.5658 1.41376 10.7608 1.44922 13.3262V13.3371C1.43694 14.9929 1.44513 16.4767 1.45331 18.0915L1.45604 18.7666C1.46013 19.5986 1.51059 20.3364 1.74927 20.8479C1.93202 21.2407 2.27299 21.508 2.88809 21.5803H14.519C15.1328 21.508 15.4737 21.2407 15.6565 20.8479C15.8952 20.3364 15.9456 19.5986 15.9497 18.7666L15.9538 18.0874C15.962 16.474 15.9688 14.9915 15.9579 13.3371V13.3262C15.992 10.7608 15.0564 10.5658 14.4086 10.4567H2.99719Z" fill="#4A4949"/>
                    </svg>
                </div>
            </div>

            <div class="columns large-4 medium-6 checkout-hat-logo checkout-hat-logo-company">
                <img src="/static/frontend/images/logos/s3stores_footer.svg"
                     alt="s3stores"
                     class="s3-logo-big checkout-hat-logo-image">
            </div>
        </div>
    </header>

    <script>
      dataProvider.set( 'stripe', {
        publicKey: "{$checkout_form->public_key}",
        paymentIntent: "{$checkout_form->stripe_payment_intent}",
        fieldId: "CheckoutForm_pbc_card_details",
      } );
    </script>
{/block}

{block "content-wrapper"}
    <div data-component="checkout"></div>
    <div class="cart_shipping-page default-content-page">
        {block "content"}{/block}
    </div>
{/block}


{block 'offcanvas-menu-left'}{/block}
{block 'search-menu'}{/block}