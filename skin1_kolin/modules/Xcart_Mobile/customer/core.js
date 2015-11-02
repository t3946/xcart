/*
$Id: core.js 78 2012-12-28 13:59:37Z skot $ 
vim: set ts=2 sw=2 sts=2 et:
*/
/**
 * Configurations
 */
var popup_opened = false;
$(document).bind('mobileinit',function(){
  $.mobile.defaultPageTransition = 'slide';
  $.mobile.page.prototype.options.addBackBtn = true;
  $.mobile.ignoreContentEnabled = true;
  
  // fix for opera brwser
  $.mobile.selectmenu.prototype.options.nativeMenu = false;
});
/**
 * Grid remake
 */
(function( $, undefined ) {
  $.fn.grid = function( options ) {
    return this.each(function() {
      var $this = $( this ),
      o = $.extend({
        grid: null
      }, options ),
      $kids = $this.children(),
      gridCols = {
        solo:1, 
        a:2, 
        b:3, 
        c:4, 
        d:5
      },
      grid = o.grid,
      iterator;
      if ( !grid ) {
        if ( $kids.length <= 5 ) {
          for ( var letter in gridCols ) {
            if ( gridCols[ letter ] === $kids.length ) {
              grid = letter;
            }
          }
        } else if ($kids.length%3 == 0) {
          grid = "b";
        } else if ($kids.length%4 == 0) {
          grid = "c";
        } else if ($kids.length%5 == 0) {
          grid = "d";
        } else {
          grid = "a";
          $this.addClass( "ui-grid-duo" );
        }
      }
      iterator = gridCols[grid];
      $this.addClass( "ui-grid-" + grid );
      $kids.filter( ":nth-child(" + iterator + "n+1)" ).addClass( "ui-block-a" );
      if ( iterator > 1 ) {
        $kids.filter( ":nth-child(" + iterator + "n+2)" ).addClass( "ui-block-b" );
      }
      if ( iterator > 2 ) {
        $kids.filter( ":nth-child(" + iterator + "n+3)" ).addClass( "ui-block-c" );
      }
      if ( iterator > 3 ) {
        $kids.filter( ":nth-child(" + iterator + "n+4)" ).addClass( "ui-block-d" );
      }
      if ( iterator > 4 ) {
        $kids.filter( ":nth-child(" + iterator + "n+5)" ).addClass( "ui-block-e" );
      }
    });
  };
})( jQuery );
$(document).bind('pagebeforechange', function(e, data){
  /**
   * Remove dialog-message nchor from the DOM to avoid the IDs conflict
   */
  if ($('#dialog-message') && $('#dialog-message').css('display') == 'none') {
    $('#dialog-message').remove();
  }
  
  /**
   * Workaround for the Photoswipe issue.
   * https://bugtracker.qtmsoft.com/view.php?id=42453
   * https://github.com/codecomputerlove/PhotoSwipe/issues/375 
   */
  if ($('.ps-carousel').length) {
    $('body').removeClass('ps-active');
    $('div.gallery-page').each(function(){
      var photoSwipe = window.Code.PhotoSwipe;
      var photoSwipeInstance = photoSwipe.getInstance($(this).attr('id'));
      if (typeof photoSwipeInstance != "undefined" && photoSwipeInstance != null) {
        photoSwipe.unsetActivateInstance(photoSwipeInstance);
      }
    });
  }
  
});
$(document).bind('pagebeforechange', function(e) {
  });

$(document).bind('pagebeforeshow', function(e, data) {
  /**
   * Correct drawing of special offers top section
   */
  if ($('.offers-short-list')) {
    $('.offers-short-list').addClass('ui-grid-a ui-body ui-body-b');
    var offers_cells = $.mobile.activePage.find('.offers-short-list').children('.offers-cell'),
    a = 97;
    $.each(offers_cells, function(index){
      $(this).addClass('ui-block-'+String.fromCharCode(a+index));
    });
    $('.offers-more-info a').button().buttonMarkup({
      mini: true,
      inline: true
    });
  }
  
  /**
   * Corret drawing of GC buttons
   */
  if ($('.gcheckout-cart-buttons .ui-btn')) {
    
    $('.gcheckout-cart-buttons')
    .find('form').attr({
      'data-ajax' : 'false',
      'data-role' : 'none',
      'data-enhance' : 'false'
    });
    var _gc_img = $('button.gcheckout-button img').attr('src');
    $('.gcheckout-cart-buttons').find('.ui-btn').replaceWith('<input class="gcheckout-button" type="image" src="'+_gc_img+'" />');
    
  }
  
  /**
   * Correct drawing of Discount coupon remove button
   */
  if ($('.dcoupons-clear')) {
    $('.dcoupons-clear a').each(function(){
      var clear_coupon_href = $(this).attr('href');
      var clear_coupon_title = $(this).children('img').attr('alt');
      $(this).replaceWith('<a href="'+ clear_coupon_href +'" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-icon="delete" data-iconpos="notext" data-theme="f" title="'+ clear_coupon_title +'" class="ui-btn ui-btn-inline ui-btn-up-f ui-shadow ui-btn-corner-all ui-btn-icon-notext"><span class="ui-btn-inner ui-btn-corner-all"><span class="ui-btn-text"></span><span class="ui-icon ui-icon-delete ui-icon-shadow">&nbsp;</span></span></a>');
    });
  }
});
/**
 * Reload window_opener
 */
$('#popup-dialog').live('pagebeforehide', function(event, data){
  
  if (data.nextPage.attr('id') != 'popup-dialog') {
    
    event.preventDefault();
    
    $.mobile.showPageLoadingMsg();
    
    $.mobile.changePage(data.nextPage.attr('data-url'), {
      reloadPage: true
    });
    
    $.mobile.hidePageLoadingMsg();
  }
  
});

$(document).bind('pageshow', function(){
  /**
   * Update minicart number
   */
  var minicart_jelly = $('.minicart-total-items');
  if (typeof minicart_total_items != 'undefined') {
    if (minicart_total_items >= 1) {
      minicart_jelly.text(minicart_total_items).show();
    }
  }
  
});
$(document).bind('pagechangefailed', function() {
  console.log('pagechangefailed');
});
$( document ).bind('pageloadfailed', function( event, data ){
  console.log('pageloadfailed');
});

/**
 * Avoiding "same IDs" conflict 
 */
$('div.product-details-page, #popup-dialog, div.cart-page').live('pagehide', function(event, ui) {
  $(event.target).remove();
});
/**
 * onLoad functions
 */
$(function() {
  
  /**
   * Back-button drawing correction
   */
  if (window.history.length <= 2) {
    $.mobile.activePage.find('a:jqmData(rel="back")').remove();
  }
  
  /**
   * Switch from mobile to common use
   */
  /******* Uncomment this code if you want to hide the switching button *******
  $(window).bind('resize pagebeforeshow', function () {
    
    var //currentPage = $.mobile.activePage,
    viewport = {
      width: $(window).width(),
      height: $(window).height()
    }
    
    if ((viewport.width >= 1000 || viewport.height >= 1000)) {
      $('.switch-mobile').css({
        'display': 'block'
      });
    } else {
      $('.switch-mobile').hide();
    }
    
  }).trigger('resize');
  ******* Uncomment this code if you want to hide the switching button *******/
  
  /**
   * Opera selectors drawings
   */
  $('.ui-select .ui-btn').removeClass('ui-select-nativeonly');
  
});
/********************************* a strange and unexpected behavior *********************************
$('.page-holder').live('swipeleft', function(){
  if ( $.mobile.hashListeningEnabled ) {
    window.history.back();
  }
  else {
    $.mobile.changePage( $.mobile.urlHistory.getPrev().url );
  }
});
$('.page-holder').live('swiperight', function(){
  if ( $.mobile.hashListeningEnabled ) {
    window.history.forward();
  }
  else {
    $.mobile.changePage( $.mobile.urlHistory.getNext().url );
  }
});
 ***************************************************************************************************/
/**
 * Popup dialog override
 */
function popupOpen(src, title, params) {
  
  var window_opener = $.mobile.activePage;
  
  var popup_data = 'popup_dialog=Y'+((typeof title != 'undefined') ? '&popup_title='+title : '');
  
  return $.mobile.changePage(src, {
    transition: 'pop',
    changeHash: true,
    role: 'dialog',
    data: popup_data,
    type: 'get'
  }) && window_opener.remove();
}
/**
 * For any case
 */
window.close = function () {
  window.history.back();
}
/**
  * Products options overriding
  */
function mobile_check_options () {
  var price = $('#product_price').text();
  
  // Drawing price on the add_to_cart button
  $('.ui-btn .currency span').text(price_format(Math.max(price, 0)));
  // Drawing in/out stock labels
  var product_avail_var = ($('#product_avail').length != 0) ? $('#product_avail option:eq(0)').val() : product_avail;
  if((product_avail_var == 0 || product_avail_var == lbl_out_stock || ($('.product-quantity-number').text() != '' && $('.product-quantity-number').text() ==  0)) || $('#exception_msg').css('display') == 'block') {
    if ($('#exception_msg').css('display') != 'block') {
      $('.product-quantity-text-top').removeClass('in-stock').text(lbl_out_stock);
    }
    if ($('#qty_select')) {
      $('#qty_select').text(lbl_out_stock);
    }
    $('#top-cart-button, #bottom-cart-button').addClass('ui-disabled');
  } else {
    if (!($('.product-quantity-text-top').hasClass('in-stock')) ||  $('#exception_msg').css('display') != 'block') {
      if ($('#exception_msg').css('display') == 'block') {
        $('.product-quantity-text-top').addClass('in-stock').text(lbl_in_stock_top);
      }
      if ($('#qty_select')) {
        $('#qty_select').text(product_avail_var);
      }
      $('#top-cart-button, #bottom-cart-button').removeClass('ui-disabled');
    }
    
  }
  
  // Hide or show save percent tag (above the add to cart button)
  if (!$.isEmptyObject($('#save_percent_box').html())) {
    if ($('#save_percent_box').css('display') == 'none') {
      $('#top-cart-button').addClass('ui-corner-top');
    } else {
      $('#top-cart-button').removeClass('ui-corner-top');
    }
  }
  
  // Wholesale prices wrapper drwaings
  if (!$.isEmptyObject($('#wl-prices').html())) {
    if ($('#wl-prices').css('display') == 'none') {
      $('#wl-prices-wrapper').hide();
    } else {
      $('#wl-prices-wrapper').show();
    }
  }
}
// override check_options
if (window.check_options) {
  var original_check_options = window.check_options;
  window.check_options = function(args){
    original_check_options(args);
    mobile_check_options();
  }
}
// override check_options for inline "onchage" event
$(function(){
  $('.product-properties select, .product-properties input').live('change',
    function(){
      
      console.log('override check_options for inline "onchage" event');
      
      if (has_options) {
        check_options(this);
      }
      
      mobile_check_options();
      
      // Draw amount in the quantity selector
      if ($(this).attr('id') == 'product_avail') {
        $('#qty_select').text($(this).val());
      }
      
      // Refresh selectors because of unbinded "change" event
      if(this.tagName == 'SELECT' && $(this).attr('id') != 'product_avail') {
        $(this).selectmenu('refresh', true);
      }

  check_wholesale($(this).val());

    });
});
/**
 * X-Payments buttons positioning
 */
function mobile_xpc_positioning (dont_move, offset) {
  var buttons_block_height = $('div.xpc-is-set').outerHeight();
  $('div.buttons-wrapper').height(buttons_block_height);
  
  if (dont_move !== true) {
    $('div.xpc-is-set').css({
      'top': -buttons_block_height-offset
    });
  }
}
function jqEscape(str)
{
    return str.replace(/([;&,\.\+\*\~':"\!\^#$%@\[\]\(\)=>\|])/g, '\\$1');
}
$(function() {
    $("#b_country").live("change", function(e) {
        $("select#"+jqEscape("address_book[B][state]")).attr("id", "b_state");
        $("#b_state").selectmenu();
        $("#b_state").selectmenu("refresh", true);
        if ($(".ui-select input").length !== 0) {
                $(".ui-select input").closest(".ui-select").addClass("state");
                $(".ui-select input").addClass("ui-input-text ui-body-c ui-corner-all ui-shadow-inset");
                $(".ui-select input").prev(".ui-btn-inner.ui-btn-corner-all").remove();
        }
        if ($(".ui-select.state").find("select").length !== 0) {
                var state = $(".ui-select .ui-select").detach();
                $(state).insertAfter(".ui-select.state");
                $(".ui-select.state").remove();					
        }

        e.preventDefault;
    });
});
