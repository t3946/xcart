<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    <link rel="stylesheet" href="{$SkinDir}/css/semantic/semantic.css">
    <link rel="stylesheet" href="{$SkinDir}/verificator/css/main.css"/>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/dimmer.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/modal.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/transition.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/progress.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/popup.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/dropdown.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/verificator/js/verificator.js" type="text/javascript"></script>

    {literal}
    <script type="text/javascript">
        // <![CDATA[
        var trans =  {/literal}{$oVerificationBatch->getSearchLinksJson()} {literal};
        var sOriginalProduct =  {/literal}{$oVerificationBatch->getOriginalLinksJson()} {literal};

        // ]]>
    </script>
    {/literal}
</head>
<body class="verifiaction">
<header id="header">
    <div class="buttons-wrap-left">
        <div class="form ui field">
            <div class="ui slider checkbox">
                <input autocomplete="off" name="newsletter" id="split-screen-checkbox" type="checkbox">
                <label>Split screen</label>
            </div>
        </div>
        <div class="wrap-left">
            <div class="three ui buttons">
                <span style="font-family:Verdana; line-height: 50px; margin-right: 15px; font-size: 18px;  position: relative; ">Compare</span>
                <button data-step-id="0" id="search_amazon_by_asin" class="ui button attached">Open product by ASIN
                </button>
                <button data-step-id="1" id="search_amazon_by_upc" class="ui attached button">Search product by UPC
                </button>
                <button data-step-id="2" id="search_amazon_by_name" class="ui right attached button">Search product by
                    Product Name
                </button>
                <span style="font-size: 18px; margin-left: 25px; margin-top:14px; font-family:Verdana;">AND</span>
                <span style="margin-left: 25px;">
                <button data-step-id="3" id="original_product" class="ui left attached button">Original product</button>
                </span>
            </div>
        </div>
    </div>
    <div class="buttons-wrap-right">
        <div class="progress-wrap">
            <div class="label">Batch progress</div>
            <div id="progress-indicator" class="ui indicating progress"
                 data-value="{$oVerificationBatch->getProductsInBatchCompletedCount()}"
                 data-total="{$oVerificationBatch->getBatchAmount()}">

                <div class="bar">
                    <div class="progress"></div>
                </div>
                <div class="label">{$oVerificationBatch->getProductsInBatchCompletedCount()}
                    /{$oVerificationBatch->getBatchAmount()}</div>
            </div>
        </div>
        <div class="buttons-right">
            <div id="conclusion_buttons" style="display:none;">
                <div class="ui vertical steps">
                    <div class="step">
                        <i class="question icon"></i>
                        <div class="content">
                            <div class="title"><a class="popup_drop_link" data-html="{$config.Amazon_Verification.amazon_verification_product_images_popup_message}" href="#">Product images</a> show</div>
                            <div class="description">
                                <div class="ui form">
                                    <div class="grouped fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input autocomplete="off" name="product_image" id="product_image_1" type="radio" value="different">
                                                <label for="product_image_1">different products</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input autocomplete="off" name="product_image" id="product_image_2" type="radio" value="same">
                                                <label for="product_image_2">the same product</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="step">
                        <i class="question icon"></i>
                        <div class="content">
                            <div class="title"><a class="popup_drop_link" data-html="{$config.Amazon_Verification.amazon_verification_product_names_popup_message}" href="#">Product names</a></div>
                            <div class="description">
                                <div class="ui form">
                                    <div class="grouped fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input autocomplete="off" name="product_names" id="product_name_1" type="radio" value="contradict">
                                                <label for="product_name_1">contradict to each other</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input autocomplete="off" name="product_names" id="product_name_2" type="radio" value="not_contradict">
                                                <label for="product_name_2">do NOT contradict</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="step">
                        <i class="question icon"></i>
                        <div class="content">
                            <div class="title">
                                <a class="popup_drop_link" data-html="{$config.Amazon_Verification.amazon_verification_make_conclusion_popup_message}" href="#">Product descriptions</a>
                            </div>
                            <div class="description">
                                <div class="ui form">
                                    <div class="grouped fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input autocomplete="off" name="product_description" id="product_description_1" type="radio" value="contradict">
                                                <label for="product_description_1">contradict to each other</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input autocomplete="off" name="product_description" id="product_description_2" type="radio" value="not_contradict">
                                                <label for="product_description_2">do NOT contradict</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="step">
                        <i class="exclamation icon"></i>
                        <div class="content">
                            <div class="title">
                                <a class="popup_drop_link" data-html="{$config.Amazon_Verification.amazon_verification_product_quantity_popup_message}" href="#">Product quantity</a> listed
                            </div>
                            <div class="description">
                                <div class="ui form buttons">
                                    <div class="grouped fields amazon_products_listed">
                                        <div class="field">
                                            <div class="ui mini input">
                                                <label>on Amazon:</label>
                                                <input autocomplete="off" name="qty_on_amazon" type="text" value="1"/>
                                            </div>
                                        </div>
                                        <div class="field">

                                            <div class="ui mini input">
                                                <label>on our website:</label>
                                                <input autocomplete="off" type="text" name="qty_on_our_website" value="1"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="conclusion_submit_button" data-asin="">
                                            <div class="ui simple button left submit-amazon-button disabled" data-action="submit">Submit</div>
                                            <div class="ui dropdown button disabled">
                                                <i class="dropdown icon"></i>
                                                <div class="menu">
                                                    <div class="item submit-amazon-button" data-action="submit_with_comment">Submit with comments</div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div data-asin="" data-batch-id="{$oVerificationBatch->getBatchId()}"
                 data-product-id="{$oVerificationBatch->getVerifiedProductId()}" class="ui buttons select"
                 id="product_not_found_button">
                <button data-action="not_found" data-class="negative" id="submit-product-not-found" class="ui left negative button">Product not found
                </button>
            </div>

        </div>
    </div>
</header>
<div class="ui inverted dimmer transition hidden segment dimmable">
    <div class="ui text loader">Loading...</div>
</div>
<div id="no_products">
    Product for verification not found
</div>
<div class="iframediv left">
</div>
<div class="iframediv right">
</div>

<div class="ui small test modal transition">
    <div class="content">
        <div class="ui form">
            <h4 class="ui dividing header">Write your comments (if you have any)</h4>

            <div class="field">
                <textarea autocomplite="off" id="verify_comment"></textarea>
            </div>
        </div>
    </div>
    <div class="actions">
        <a id="negative_conclusion">Cancel</a>
        <div class="ui positive right labeled icon button">
            Submit
            <i class="checkmark icon"></i>
        </div>
    </div>
</div>
</body>
</html>