{extends "base.tpl"}
{block "content"}

    <div class="content-container product">
        <section class="breadcrumb-section">
            <ol class="breadcrumb">
                <li class="breadcrumb__back hidden-xs"><a href="#"></a></li>
                <li class="breadcrumb__item"><a href="#" class="breadcrumb__link">ArtistSupplySource.com</a></li>
                <li class="breadcrumb__item"><a href="#" class="breadcrumb__link">Painting and Painting Accessories</a></li>
                <li class="breadcrumb__item"><a href="#" class="breadcrumb__link">Oil Painting sets</a></li>
                <li class="current breadcrumb__item">Gamblin Artists’ Grade oil paint product line</li>
            </ol>
        </section>
        <div class="main-container-desktop">
            <section class="product product-group">
                <div class="product__main-wrapper">
                    <div class="product__head-info">
                        <h1 class="product__title">Gamblin Artists’ Grade oil paint product line</h1>
                        <img src="/static/frontend/dist/images/product/1280/shape-29-copy.png" alt="Retail Trust" class="product__retail-trust-icon" />
                        <span class="product__sku">
                                                SKU: MFW-1275
                                            </span>
                        <a href="#" class="product__link">
                            <img src="/static/frontend/dist/images/confidence/verified_secured.png" alt="Verified Secured"
                                 class="product__image_verified-secured" />
                        </a>
                    </div>
                    <div class="row product__view-container">
                        <div class="col col-xs-13 col col-lg-7">
                            <section class="product__view slider-vertical" id="product__view-block">
                                <div class="product__view__active-wrapper slider-vertical__active-wrapper">
                                    <div class="product__view__frame slider-vertical__frame" id="product__view_frame">
                                        <ul>
                                            <li class="product__view__item slider-vertical__item active"><a href="#" class="slider-vertical__item_image-link"><img src="/static/frontend/dist/images/product/1280/alv-dav111-1.png" alt="Item1"
                                                                                                                                                                   class="slider-vertical__item_image"></a>

                                            </li>
                                            <li class="product__view__item slider-vertical__item"><a class="slider-vertical__item_image-link" href="#"><img src="/static/frontend/dist/images/category/1280/198-media-catalog-product-7-g-7g12643-1-jpg.png" alt="Item2"
                                                                                                                                                            class="slider-vertical__item_image"></a>


                                            </li>
                                            <li class="product__view__item slider-vertical__item"><a class="slider-vertical__item_image-link video" href="#"><img src="/static/frontend/dist/images/category/1280/alv-rack08-1-jpg.png" alt="Item3"
                                                                                                                                                                  class="slider-vertical__item_image"></a>


                                            </li>

                                        </ul>
                                    </div>
                                    <div class="product__view__controls slider-vertical__controls">
                                        <a href="#" class="product__view__controls_btn slider-vertical__controls_btn product__view__controls_prev-page slider-vertical__controls_prev-page"></a>
                                        <a href="#" class="product__view__controls_btn slider-vertical__controls_btn product__view__controls_next-page slider-vertical__controls_next-page"></a>
                                    </div>
                                    <div class="product__view__scrollbar slider-vertical__scrollbar">
                                        <div class="product__view__scrollbar_handle slider-vertical__scrollbar_handle">
                                            <div class="product__view__scrollbar_mousearea slider-vertical__scrollbar_mousearea"></div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="col col-xs-47 col col-lg-53 product__full-view-container">
                            <div class="full-view" style="background-image: url(/static/frontend/dist/images/product/1280/alv-dav111-1.png);"></div>
                            <div class="product__share">
                                <a tabindex="0" class="product__share-btn product__share-btn_plus" data-toggle="popover" data-trigger="focus"
                                   data-placement="top" data-content="<a href='#'><img src='/static/frontend/dist/images/social/facebook1.png' /></a>
                                                       <a href='#'><img src='/static/frontend/dist/images/social/twitter1.png' /></a>
                                                       <a href='#'><img src='/static/frontend/dist/images/social/youtube1.png' /></a>
                                                       <a href='#'><img src='/static/frontend/dist/images/social/googleplus1.png' /></a>
                                                       <a href='#'><img src='/static/frontend/dist/images/social/email.png' /></a>"><img
                                            src="/static/frontend/dist/images/social/plus-popover.png" alt="Share"></a>
                                <a href="#" class="product__share-btn product__share-btn_pinterest"><img
                                            src="/static/frontend/dist/images/social/pinterest-64x64.png" alt="Email"></a>
                            </div>
                            <a href="#" class="product__view_hint hidden-lg">Tap to Zoom</a>
                            <a href="#" class="product__view_hint hidden-xs hidden-sm hidden-md">Click above to zoom</a>
                        </div>
                    </div>
                </div>
                <div class="product__minimum-order-amount product__info-block hidden-xs">
                    <img src="/static/frontend/dist/images/product/1280/5_the_min_order_amount_for_this_product_line.svg"
                         alt="The minimum order amount" class="product__minimum-order-amount_image product__info_image">

                    <div class="product__minimum-order-amount_caption product__info_caption">
                        <p>The minimum order amount for
                            this product line is $ 25.00</p>
                    </div>
                </div>
                <div class="product__free-shipping product__info-block hidden-xs">
                    <img src="/static/frontend/dist/images/product/1280/3_free_shipping.svg"
                         alt="Free Shipping" class="product__free-shipping_image product__info_image">

                    <div class="product__free-shipping_caption product__info_caption">
                        <p>Free Shipping within USA Contiguous Only</p>
                    </div>
                </div>

                {include "demo/product/_group.tpl"}


                <div class="product__purchase-info">
                    <table class="product__purchase-info_table">
                        <thead>
                        <tr>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="product__main-row">
                            <td>US$ 238.77</td>
                            <td class="product__quantity">
                                <div class="btn-group">
                                    <a href="" class="btn quantity_modify quantity_dec">-</a>
                                    <input type="number" min="1" max="9999" class="btn quantity_input" name="quantity" id="quantity" value="1" />
                                    <a href="" class="btn quantity_modify quantity_inc active">+</a>
                                </div>
                            </td>
                            <td>US$ 238.77</td>
                        </tr>
                        <tr>
                            <td>US$ 234.08</td>
                            <td>2</td>
                            <td class="hidden-xs"></td>
                        </tr>
                        <tr>
                            <td>US$ 232.66</td>
                            <td>3</td>
                            <td class="hidden-xs"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="product__add-to-cart">
                    <a href="#" class="product__add-to-cart_btn">Add to cart</a>
                </div>

                <a href="#" class="product__link">
                    <img src="/static/frontend/dist/images/confidence/verified_secured.png" alt="Verified Secured"
                         class="product__image_verified-secured" />
                </a>

                <div class="product__accordion-info-mob accordion-mob">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#product_mob_description">Description</a>
                                </h4>
                            </div>
                            <div id="product_mob_description" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="product__options">
                                        <h5 class="product__options_title">Options</h5>
                                        <table class="product__options_table">
                                            <tr>
                                                <td class="product__options_name">Production</td>
                                                <td>Union Rubber. Inc</td>
                                            </tr>
                                            <tr>
                                                <td class="product__options_name">Brand</td>
                                                <td><a href="../category/brand.html">Bestine</a></td>
                                            </tr>
                                            <tr>
                                                <td class="product__options_name">Size</td>
                                                <td>16 oz</td>
                                            </tr>
                                            <tr>
                                                <td class="product__options_name">Accessories</td>
                                                <td>not</td>
                                            </tr>
                                            <tr>
                                                <td class="product__options_name">Barcode</td>
                                                <td>UPC: 089665002017</td>
                                            </tr>
                                            <tr>
                                                <td class="product__options_name">Instructions</td>
                                                <td><a href="#" class="product__options_file product__options_file_pdf"><img
                                                                src="/static/frontend/dist/images/product/1280/pdf_file.svg"
                                                                alt="Bestine Solvent Thinner"> Bestine Solvent
                                                        Thinner <span class="small">(10.3mb)</span></a></td>
                                            </tr>
                                            <tr>
                                                <td class="product__options_name">Product Sheet</td>
                                                <td><a href="#" class="product__options_file product__options_file_xls"><img
                                                                src="/static/frontend/dist/images/product/1280/xls_file.svg"
                                                                alt="Bestine Solvent Thinner">Bestine Solvent
                                                        Thinner <span class="small">(10.3mb)</span></a></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="product__description text-more-wrapper">
                                        <h5 class="product__description_title">Description</h5>
                                        <ul class="description__list text-block">
                                            <li class="description__item text"><p>Durable phenolic straightedge blade is equipped with clear acrylic inking edge and nylon
                                                    bearings for smooth motion and even support over entire work surface.</p></li>
                                            <li class="description__item text"><p>Blade remains parallel at all times,
                                                    gliding smoothly up and down on guide wires.</p></li>
                                            <li class="description__item text"><p>Work surface is protected with a layer of pliable VYCO board cover.</p></li>
                                            <li class="description__item_more text-more"><p>Durable phenolic straightedge blade is equipped with clear acrylic inking edge and nylon
                                                    bearings for smooth motion and even support over entire work surface.</p></li>
                                            <li class="description__item_more text-more"><p>Blade remains parallel at all times,
                                                    gliding smoothly up and down on guide wires.</p></li>
                                            <li class="description__item_more text-more"><p>Work surface is protected
                                                    with a layer of pliable VYCO board cover.</p></li>
                                        </ul>

                                        <a href="#" class="description__read-more read-more"></a>
                                    </div>


                                </div>
                            </div>
                        </div>


                        <!--<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#product_mob_brand">Brand</a>
                                </h4>
                            </div>
                            <div id="product_mob_brand" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <h5 class="product__brand_title"><img
                                            src="/static/frontend/dist/images/category/1280/layer-1.png"
                                            alt="">Bestine Solvent</h5>

                                    <div class="product__brand_text-block text-more-wrapper">
                                        <p class="product__brand_text text text-block">
                                            Since our founding, Gamblin Artists Colors has handcrafted luscious oil colors and
                                            contemporary mediums true to the
                                            working properties of traditional materials. We believe every painting deserves to
                                            stand the test of time and are proud to provide artists with safer, more permanent oil painting materials.
                                        </p>

                                        <p class="product__brand_text_more text-more">
                                            Since our founding, Gamblin Artists Colors has handcrafted luscious oil colors and
                                            contemporary mediums true to the
                                            working properties of traditional materials. We believe every painting deserves to
                                            stand the test of time and are proud to provide artists with safer, more permanent oil painting materials.
                                        </p>

                                        <a href="#" class="product__brand__read-more read-more"></a>

                                    </div>

                                    <a href="#" class="product__brand_see-all">See all Bestine products</a>
                                </div>
                            </div>
                        </div>-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#product_mob_shipping">Shipping</a>
                                </h4>
                            </div>
                            <div id="product_mob_shipping" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="product__shipping_specs">
                                        <h5 class="shipping__specs_title">Shipping specs</h5>
                                        <table class="shipping__specs_table">
                                            <tr>
                                                <td class="shipping__specs_name">Weight</td>
                                                <td>33.00 Lbs</td>
                                            </tr>
                                            <tr>
                                                <td class="shipping__specs_name">Dimensions</td>
                                                <td>47" x 35" x 3"</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="product__shipping_from">
                                        <h5 class="shipping__from_title">Shipping from</h5>

                                        <p class="shipping__from_text">
                                            This product is shipped from our
                                            warehouse in Wallington, NJ, USA.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#product_mob_our-garantee">Our guarantee</a>
                                </h4>
                            </div>
                            <div id="product_mob_our-garantee" class="panel-collapse collapse">
                                <div class="panel-body">
                                    This product is brand new and includes the manufacturer's warranty,
                                    so you can buy with confidence.
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#product_mob_disclaimer">Disclaimer</a>
                                </h4>
                            </div>
                            <div id="product_mob_disclaimer" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Actual color and texture may vary, use as a guide only.
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#product_mob_return-policy">Return policy</a>
                                </h4>
                            </div>
                            <div id="product_mob_return-policy" class="panel-collapse collapse">
                                <div class="panel-body">
                                    A 25% Handling Charge is levied against all authorized returns except
                                    those due to our error. Unauthorized returns are subject to a 40%
                                    Handling Charge. Damages & defects must be reported to us within 7 days.
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#product_mob_questions">Product questions</a>
                                </h4>
                            </div>
                            <div id="product_mob_questions" class="panel-collapse collapse">
                                <div class="panel-body panel-body__form">
                                    <div class="product-questions__form-wrapper">
                                        <span class="product-questions__hint hint">Please submit your product question using this form. All fields are required.</span>
                                        <div class="product-questions__name-block product-questions__block block">
                                            <label for="product-questions__name">Your first name<span class="require">*</span></label>
                                            <input type="text" id="product-questions__name" value="Albert" class="product-questions__name">
                                            <div class="product-questions__remove-text remove-text"></div>
                                            <div class="product-questions__valid valid">
                                                <span class="product-questions__ok ok"></span><span
                                                        class="product-questions__error error"></span>
                                            </div>
                                        </div>
                                        <div class="product-questions__email-block product-questions__block block">
                                            <label for="product-questions__email">Your email<span class="require">*</span></label>
                                            <input type="text" id="product-questions__email" placeholder="albert.einstein@gmail.com" class="product-questions__email">
                                            <div class="product-questions__remove-text remove-text"></div>
                                            <div class="product-questions__valid valid">
                                                <span class="product-questions__ok ok"></span><span
                                                        class="product-questions__error error"></span>
                                            </div>
                                        </div>
                                        <div class="product-questions__phone-block product-questions__block block">
                                            <label for="product-questions__phone">Your phone<span class="require">*</span></label>

                                            <div class="product-questions__phone-block-1 block-1">
                                                <input type="text" id="product-questions__phone" placeholder="(609) 734-8000" class="product-questions__phone">
                                                <div class="product-questions__remove-text remove-text"></div>
                                            </div>
                                            <span class="product-questions__x x">X</span>
                                            <div class="product-questions__phone-block-2 block-2">
                                                <input type="text" id="product-questions__phone2" placeholder="" class="product-questions__phone">
                                                <div class="product-questions__remove-text remove-text"></div>
                                            </div>
                                            <div class="product-questions__valid valid">
                                                <span class="product-questions__ok ok"></span><span
                                                        class="product-questions__error error"></span>
                                            </div>
                                        </div>
                                        <div class="product-questions__question-block product-questions__block block">
                                            <label for="product-questions__question">Product question<span class="require">*</span></label>
                                            <span class="product-questions__question-hint hint">Please don't mention your email and your phone in this field.</span>
                                            <textarea rows="4" cols="35" id="product-questions__question" placeholder="Please type your product question here" class="product-questions__question"></textarea>
                                            <div class="product-questions__remove-text remove-text"></div>
                                            <div class="product-questions__valid valid">
                                                <span class="product-questions__ok ok"></span><span
                                                        class="product-questions__error error"></span>
                                            </div>
                                        </div>
                                        <a href="#" class="product-questions__submit submit button_subordinary">Submit question</a>
                                    </div>

                                    <div class="product-questions__answers-wrapper answers-wrapper">
                                        <div class="product-questions__answers-block answers-block">
                                            <div class="sub-block question">
                                                <span class="icon question_icon">Q</span>

                                                <div class="question_quote quote">This is measured
                                                    at 32x42, but I am wondering will a 32x40 frame
                                                    fit in
                                                </div>
                                                <span class="question_info info">asked by Eavan on Oct 07, 2015</span>
                                            </div>
                                            <div class="sub-block answer">
                                                <span class="icon answer_icon">A</span>

                                                <div class="answer_quote quote">Item should fit. The thickness of
                                                    the item could make a difference. Item should fit.
                                                    The thickness of the item could make a difference. Thank
                                                </div>
                                                <span class="answer_info info">answered by Milan (Staff) on Oct 08, 2015</span>
                                            </div>
                                        </div>
                                        <div class="product-questions__answers-block answers-block">
                                            <div class="sub-block question">
                                                <span class="icon question_icon">Q</span>

                                                <div class="question_quote quote">This is measured
                                                    at 32x42, but I am wondering will a 32x40 frame
                                                    fit in
                                                </div>
                                                <span class="question_info info">asked by Eavan on Oct 07, 2015</span>
                                            </div>
                                            <div class="sub-block answer">
                                                <span class="icon answer_icon">A</span>

                                                <div class="answer_quote quote">Item should fit. The thickness of
                                                    the item could make a difference. Item should fit.
                                                    The thickness of the item could make a difference. Thank
                                                </div>
                                                <span class="answer_info info">answered by Milan (Staff) on Oct 08, 2015</span>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </div>

        {include "demo/blocks/sliders/_customers-also-bought.tpl"}
        {include "demo/blocks/sliders/_recently_viewed.tpl"}

    </div>

{/block}