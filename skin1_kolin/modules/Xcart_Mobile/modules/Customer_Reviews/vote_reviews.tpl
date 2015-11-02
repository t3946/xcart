{*
$Id: vote_reviews.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{capture name=dialog}
  {if $config.Customer_Reviews.customer_reviews eq 'Y'}
    {if $reviews}
      <div class="creviews-reviews-list">
        {foreach from=$reviews item=r}
          <div class="ui-body ui-body-e">
            {$lng.lbl_author}: <strong>{$r.email|default:$lng.lbl_unknown}</strong><br />
            {$r.message|nl2br|amp}
          </div>
        {/foreach}
      </div>
    {else}
      <div class="creviews-reviews-list">{$lng.txt_no_customer_reviews}</div>
    {/if}

    {if $allow_review}
      {include file="customer/subheader.tpl" title=$lng.lbl_add_your_review}
      {if $allow_add_review}
        <form method="post" action="product.php">
          <input type="hidden" name="mode" value="add_review" />
          <input type="hidden" name="productid" value='{$product.productid}' />
          <table cellspacing="1" class="data-table" summary="{$lng.lbl_add_your_review|escape}">
            <tr>
              <td class="data-name"><label for="review_author">{$lng.lbl_your_name}</label>:</td>
              <td class="data-required">*</td>
              <td>
                <input type="text" size="24" maxlength="128" name="review_author" id="review_author" value="{$review.author|amp}" />
                {if $review.author eq "" and $review.error}
                  <span class="data-required">&lt;&lt;</span>
                {/if}
              </td>
            </tr>
            <tr>
              <td class="data-name"><label for="review_message">{$lng.lbl_your_message}</label>:</td>
              <td class="data-required">*</td>
              <td>
                <textarea cols="40" rows="4" name="review_message" id="review_message">{$review.message|amp}</textarea>
                {if $review.message eq "" and $review.error}
                  <span class="data-required">&lt;&lt;</span>
                {/if}
              </td>
            </tr>
            {include file="customer/buttons/button.tpl" button_title=$lng.lbl_add_review type="input" assign="submit_button"}
            {if $active_modules.Image_Verification and $show_antibot.on_reviews eq 'Y'}
              {include file="modules/Image_Verification/spambot_arrest.tpl" mode="data-table" id=$antibot_sections.on_reviews antibot_err=$review.antibot_err button_code=$submit_button antibot_name_prefix='_on_reviews'}
            {else}
              <tr>
                <td colspan="2">&nbsp;</td>
                <td class="button-row">
                  {$submit_button}
                </td>
              </tr>
            {/if}
          </table>
        </form>
      {else}
        {$lng.txt_you_already_voted}
      {/if}
    {/if}
  {/if}
{/capture}
{$smarty.capture.dialog}

