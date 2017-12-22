{*
$Id: order_invoice.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $customer ne ''}
{assign var="_userinfo" value=$customer}{else}{assign var="_userinfo" value=$userinfo}
{/if}
{config_load file="$skin_config"}
{if $is_nomail ne 'Y'}
  <br />
{/if}
<img src="{$ImagesDir}/companyname_small.gif" alt="" />
<ul data-role="listview" data-theme="d" data-divider-theme="d">
  <li data-role="list-divider">
    {if $order.status eq 'A' or $order.status eq 'P' or $order.status eq 'C'}
      {$lng.lbl_receipt}
    {else}
      {$lng.lbl_invoice}
    {/if}
  </li>
  <li>
    <div class="ui-grid-a">
      <div class="ui-block-a">
        <div class="mob-table">
          <div class="mob-table-row">
            <span class="mob-table-cell mob-th">
              {$lng.lbl_date}:  
            </span>
            <span class="mob-table-cell">
              {$order.date|date_format:$config.Appearance.datetime_format}
            </span>
          </div>
          <div class="mob-table-row">
            <span class="mob-table-cell mob-th">
              {$lng.lbl_order_id}:
            </span>
            <span class="mob-table-cell">
              #{$order.orderid}
            </span>
          </div>
          <div class="mob-table-row">
            <span class="mob-table-cell mob-th">
              {$lng.lbl_order_status}:
            </span>
            <span class="mob-table-cell">
              {include file="main/order_status.tpl" status=$order.status mode="static"}
            </span>
          </div>
          <div class="mob-table-row">
            <span class="mob-table-cell mob-th">
              {$lng.lbl_payment_method}:
            </span>
            <span class="mob-table-cell">
              {$order.payment_method}
            </span>
          </div>
          <div class="mob-table-row">
            <span class="mob-table-cell mob-th">
              {$lng.lbl_delivery}:
            </span>
            <span class="mob-table-cell">
              {$order.shipping|trademark:'use_alt'|default:$lng.txt_not_available}
            </span>
          </div>
          {if $order.tracking}
            <div class="mob-table-row">
              <span class="mob-table-cell mob-th">
                {$lng.lbl_tracking_number}:
              </span>
              <span class="mob-table-cell">
                {$order.tracking|escape}
              </span>
            </div>
          {/if}
        </div>
      </div>
      <div class="ui-block-b">
        <h3>{$config.Company.company_name}</h3>
        {$config.Company.location_address}, {$config.Company.location_city}<br />
        {strip}
          {$config.Company.location_zipcode}
          {if $config.Company.location_country_has_states}
            , {$config.Company.location_state_name}
          {/if}<br />
        {/strip}
        {$config.Company.location_country_name}<br />
        <div class="mob-table">
          {if $config.Company.company_phone}
            <div class="mob-table-row">
              <span class="mob-table-cell mob-th">
                {$lng.lbl_phone_1_title}: 
              </span>
              <span class="mob-table-cell">
                {$config.Company.company_phone}
              </span>
            </div>
          {/if}
          {if $config.Company.company_phone_2}
            <div class="mob-table-row">
              <span class="mob-table-cell mob-th">
                {$lng.lbl_phone_2_title}:
              </span>
              <span class="mob-table-cell">
                {$config.Company.company_phone_2}
              </span>
            </div>
          {/if}
          {if $config.Company.company_fax}
            <div class="mob-table-row">
              <span class="mob-table-cell mob-th">
                {$lng.lbl_fax}:
              </span>
              <span class="mob-table-cell">
                {$config.Company.company_fax}
              </span>
            </div>
          {/if}
          {if $config.Company.orders_department}
            <div class="mob-table-row">
              <span class="mob-table-cell mob-th">
                {$lng.lbl_email}:
              </span>
              <span class="mob-table-cell">
                {$config.Company.orders_department}
              </span>
            </div>
          {/if}
          {if $order.applied_taxes}
            {foreach from=$order.applied_taxes key=tax_name item=tax}
              <div class="mob-table-row">
                <span class="mob-table-cell">
                  {$tax.regnumber}
                </span>
              </div>
            {/foreach}
          {/if}
        </div>
      </div>
    </div>
  </li>
  <li>
    <div class="mob-table">
      <div class="mob-table-row">
        <span class="mob-table-cell mob-th">{$lng.lbl_email}:</span>
        <span class="mob-table-cell">{$order.email}</span>
      </div>
      {if $_userinfo.default_fields.title}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_title}:</span>
          <span class="mob-table-cell">{$order.title}</span>
        </div>
      {/if}
      {if $_userinfo.default_fields.firstname}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_first_name}:</span>
          <span class="mob-table-cell">{$order.firstname}</span>
        </div>
      {/if}
      {if $_userinfo.default_fields.lastname}
        <div class="mob-table-row">
          <td{if $is_nomail eq 'Y'} nowrap="nowrap"{/if}><strong>{$lng.lbl_last_name}:</span>
              <span class="mob-table-cell">{$order.lastname}</span>
        </div>
      {/if}
      {if $_userinfo.default_fields.company}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_company}:</span>
          <span class="mob-table-cell">{$order.company}</span>
        </div>
      {/if}
      {if $_userinfo.default_fields.tax_number}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_tax_number}:</span>
          <span class="mob-table-cell">{$order.tax_number}</span>
        </div>
      {/if}
      {if $_userinfo.default_fields.url}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_url}:</span>
          <span class="mob-table-cell">{$order.url}</span>
        </div>
      {/if}
      {foreach from=$_userinfo.additional_fields item=v}
        {if $v.section eq 'P' and $v.value ne ''}
          <div class="mob-table-row">
            <span class="mob-table-cell mob-th">{$v.title}:</span>
            <span class="mob-table-cell">{$v.value}</span>
          </div>
        {/if}
      {/foreach}
    </div>
  </li>

  <li data-role="list-divider">
    {$lng.lbl_billing_address}
  </li>
  <li>
    <div class="mob-table">
      {if $_userinfo.default_address_fields.title}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_title}:</span>
          <span class="mob-table-cell">{$order.b_title|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.firstname}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_first_name}:</span>
          <span class="mob-table-cell">{$order.b_firstname|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.lastname}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_last_name}:</span>
          <span class="mob-table-cell">{$order.b_lastname|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.address}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_address}:</span>
          <span class="mob-table-cell">{$order.b_address|escape}<br />{$order.b_address_2|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.city}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_city}:</span>
          <span class="mob-table-cell">{$order.b_city|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.county and $config.General.use_counties eq 'Y'}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_county}:</span>
          <span class="mob-table-cell">{$order.b_countyname|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.state}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_state}:</span>
          <span class="mob-table-cell">{$order.b_statename|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.country}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_country}:</span>
          <span class="mob-table-cell">{$order.b_countryname|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.zipcode}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_zip_code}:</span>
          <span class="mob-table-cell">{include file="main/zipcode.tpl" val=$order.b_zipcode zip4=$order.b_zip4 static=true}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.phone}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_phone}:</span>
          <span class="mob-table-cell">{$order.b_phone|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.fax}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_fax}:</span>
          <span class="mob-table-cell">{$order.b_fax|escape}</span>
        </div>
      {/if}
      {foreach from=$_userinfo.additional_fields item=v}
        {if $v.section eq 'B' and $v.value ne ''}
          <div class="mob-table-row">
            <span class="mob-table-cell mob-th">{$v.title}:</span>
            <span class="mob-table-cell">{$v.value}</span>
          </div>
        {/if}
      {/foreach}
    </div>
  </li>

  <li data-role="list-divider">
    {$lng.lbl_shipping_address}
  </li>
  <li>
    <div class="mob-table">
      {if $_userinfo.default_address_fields.title}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_title}:</span>
          <span class="mob-table-cell">{$order.s_title|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.firstname}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_first_name}:</span>
          <span class="mob-table-cell">{$order.s_firstname|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.lastname}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_last_name}:</span>
          <span class="mob-table-cell">{$order.s_lastname|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.address}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_address}:</span>
          <span class="mob-table-cell">{$order.s_address|escape}<br />{$order.s_address_2|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.city}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_city}:</span>
          <span class="mob-table-cell">{$order.s_city|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.county and $config.General.use_counties eq 'Y'}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_county}:</span>
          <span class="mob-table-cell">{$order.s_countyname|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.state}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_state}:</span>
          <span class="mob-table-cell">{$order.s_statename|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.country}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_country}:</span>
          <span class="mob-table-cell">{$order.s_countryname|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.zipcode}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_zip_code}:</span>
          <span class="mob-table-cell">{include file="main/zipcode.tpl" val=$order.s_zipcode zip4=$order.s_zip4 static=true}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.phone}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_phone}:</span>
          <span class="mob-table-cell">{$order.s_phone|escape}</span>
        </div>
      {/if}
      {if $_userinfo.default_address_fields.fax}
        <div class="mob-table-row">
          <span class="mob-table-cell mob-th">{$lng.lbl_fax}:</span>
          <span class="mob-table-cell">{$order.s_fax|escape}</span>
        </div>
      {/if}
      {foreach from=$_userinfo.additional_fields item=v}
        {if $v.section eq 'S' and $v.value ne ''}
          <div class="mob-table-row">
            <span class="mob-table-cell mob-th">{$v.title}:</span>
            <span class="mob-table-cell">{$v.value}</span>
          </div>
        {/if}
      {/foreach}
    </div>
  </li>
  {assign var="empty_add_info" value="true"}
  {capture name="add_info"}
    <div class="mob-table">
      {foreach from=$_userinfo.additional_fields item=v}
        {if $v.section eq 'A' and $v.value ne ''}
          {assign var="empty_add_info" value="false"}
          <div class="mob-table-row">
            <span class="mob-table-cell mob-th">{$v.title}</span>
            <span class="mob-table-cell">{$v.value}</span>
          </div>
        {/if}
      {/foreach}
    </div>
  {/capture}
  {if $empty_add_info eq "false"}
    <li data-role="list-divider">
      {$lng.lbl_additional_information}
    </li>
    <li>
      {$smarty.capture.add_info}
    </li>
  {/if}

  {if $config.Email.show_cc_info eq "Y" and $show_order_details eq "Y" and $order.details ne ""}
    <li data-role="list-divider">
      {$lng.lbl_order_payment_details}
    </li>
  {/if}

  {if ($config.Email.show_cc_info eq "Y" and $show_order_details eq "Y" and $order.details ne "") or $order.netbanx_reference}
    <li>
      <div class="mob-table">
        <div class="mob-table-row">
          <span class="mob-table-cell">{$order.details|order_details_translate|escape|replace:"\n":"<br />"}</span>
        </div>
        {if $order.extra.advinfo ne ""}
          <div class="mob-table-row">
            <span class="mob-table-cell">{$order.extra.advinfo|escape|replace:"\n":"<br />"}</span>
          </div>  
        {/if}

        {if $order.netbanx_reference}
          <div class="mob-table-row">
            <span class="mob-table-cell">NetBanx Reference: {$order.netbanx_reference}</span>
          </div>
        {/if}
      </div>
    </li>
  {/if}
  <li class="order-data">
    {include file="mail/html/order_data.tpl"}
  </li>
  {if $order.need_giftwrap eq "Y"}
    <li>
      {include file="modules/Gift_Registry/gift_wrapping_invoice.tpl" show=message}
    </li>
  {/if}
  {if $order.customer_notes ne ""}
    <li data-role="list-divider">
      {$lng.lbl_customer_notes}
    </li>
    <li>
      {$order.customer_notes|nl2br}
    </li>
  {/if}
</ul>
