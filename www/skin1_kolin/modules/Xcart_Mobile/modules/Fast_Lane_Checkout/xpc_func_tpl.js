/*
 $Id$ 
 vim: set ts=2 sw=2 sts=2 et:
 */
/*{* 
 Attention to the SMARTY code in JS comments.
 *}*/
var xpc_paymentids = [];
/*{if $active_modules.XPayments_Connector && $config.XPayments_Connector.xpc_use_iframe eq 'Y'}*/
var paymentid = '{$payment_data.paymentid}';
/*{if $xcart_mobile_config.submit_order_dlg_disabled ne "Y"}*/
var submit_order_dlg_enabled = true;
/*{else}*/
var submit_order_dlg_enabled = false;
/*{/if}*/
var payment_loading_message = '{$lng.lbl_payment_form_loading|escape}';
var terms_cond_note = '{$lng.txt_mobile_terms_and_conditions_note}';
var current_location = '{$current_location}';
var txt_submit_order = '{$lng.lbl_submit_order}';
/*{if $config.version gte '4.5.5'}*/
var offset = 0;
/*{else}*/
var offset = 80;
/*{/if}*/
/*{if $config.XPayments_Connector.xpc_api_version lt '1.2'}*/
var xpc_api_version = 'old';
/*{else}*/
var xpc_api_version = 'new';
/*{/if}*/
var frame_height = (xpc_api_version === 'old') ? 470 : 270;
/*{literal}*/
function checkIframe(ifr) {
  var key = (+new Date) + "" + Math.random();
  try {
    var global = ifr.contentWindow;
    global[key] = "asd";
    return global[key] === "asd";
  }
  catch (e) {
    return false;
  }
}
$(function() {
  if ($('iframe.xpc_iframe').is('iframe')) {
    $.mobile.loading('show', {
      theme: "b",
      text: payment_loading_message,
      textVisible: true
    });
    $.mobile.activePage.after('<div class="page-loading" />');
    $('iframe.xpc_iframe').attr('src', 'payment/cc_xpc_iframe.php?paymentid=' + paymentid);
    var buttons_block_html = $('div.xpc-is-set .buttons-itself').html() + (submit_order_dlg_enabled ? '<input type="checkbox" id="accept_terms_xpc" /><label for="accept_terms_xpc">' + terms_cond_note + '</label>' : '');
    buttons_block_html += '<button type="submit">' + txt_submit_order + '</button>';
    $('iframe.xpc_iframe').load(function() {
      $('div.page-loading').remove();
      $.mobile.loading('hide');
      $('#xpc_iframe_wrapper').show();
      $(this).css({
        'width': 'auto',
        'height': frame_height
      });
      $.mobile.silentScroll($(this).offset().top);
      if (submit_order_dlg_enabled) {
        $('div.button-hider').toggle(!$('#accept_terms').is(':checked'));
      }
      if (checkIframe($(this).get(0))) {
        /* New frame height value for nested frame */
        frame_height = 660;
        $(this).css({
          'width': '100%',
          'height': frame_height
        });

        var xpc_iframe = $(this).contents();
        $('div.button-hider, div.buttons-wrapper').remove();
        xpc_iframe.find('html').css({
          'background-color': 'transparent'
        });
        xpc_iframe.find('body').addClass('xpc_iframe');
        xpc_iframe.find('head')
                .append('<link rel="stylesheet" type="text/css" href="' + current_location + '/skin/common_files/modules/Xcart_Mobile/lib/jquery.mobile.css" />')
                .append('<link rel="stylesheet" type="text/css" href="' + current_location + '/skin/common_files/modules/Xcart_Mobile/lib/jquery.mobile.core.css" />')
                .append('<link rel="stylesheet" type="text/css" href="' + current_location + '/skin/common_files/modules/Xcart_Mobile/css/main.css" />');
        $('<tr><td colspan="3"><div class="ui-body ui-body-e">' + buttons_block_html + '</div></td></tr>').insertBefore(xpc_iframe.find('#buttonRow'));
        $('div.xpc-is-set').remove();
        xpc_iframe.find('td.submit-cell').attr('colspan', '3');
        xpc_iframe.find('#cardExpire').find('select').parent().wrapInner('<div class="ui-corner-all ui-controlgroup ui-controlgroup-horizontal" />');
        xpc_iframe.find('select').selectmenu();
        xpc_iframe.find('select').selectmenu('refresh', true);
        xpc_iframe.find('button').attr('data-theme', 'b').button();
        xpc_iframe.find('button').button('refresh');
        if (submit_order_dlg_enabled) {
          xpc_iframe.find('button').button($('#accept_terms_xpc').is(':checked') ? 'enable' : 'disable');
        }
        xpc_iframe.find('input[type="text"]').textinput();
        xpc_iframe.find('input[type="checkbox"]').checkboxradio();
        xpc_iframe.find('input[type="checkbox"]').checkboxradio('refresh');
        if (submit_order_dlg_enabled) {
          xpc_iframe.find('.ui-checkbox').click(function() {
            var checked_state = $(this).children('input[type="checkbox"]').is(':checked');
            $(this).children('input[type="checkbox"]').attr('checked', !checked_state);
            xpc_iframe.find('button').button(!checked_state ? 'enable' : 'disable');
            $(this).children('input[type="checkbox"]').checkboxradio('refresh');
          });
        }
      }
      mobile_xpc_positioning(false, offset);
    });
    $(window).bind('pagebeforeshow resize orientationchange', mobile_xpc_positioning(true, offset));
    $(window).bind('message', function() {
      $('iframe.xpc_iframe').height(frame_height);
    });
    $('#accept_terms').on('change', function() {
      $('div.button-hider').toggle(!$(this).is(':checked'));
    });
  } /*is_iframe checking end*/
});
/*{/literal}
 {/if}*/

