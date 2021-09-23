// $Id: bulk_management.js,v 1.0 2012/03/21 06:55:36 kirill Exp $
function bpm_row_click(el) {
    var div = $(el).find('div');
 
    if (div.hasClass('bpm_plus')) {
        div.removeClass('bpm_plus');
        div.addClass('bpm_minus');
        
        $(el).parent().parent().find('div.bpm_one_row').each(function() {
            $(this).removeClass('bpm_one_row');
            $(this).addClass('bpm_multi_row');
        });
    } else {
        div.removeClass('bpm_minus');
        div.addClass('bpm_plus');
        
        $(el).parent().parent().find('div.bpm_multi_row').each(function() {
            $(this).removeClass('bpm_multi_row');
            $(this).addClass('bpm_one_row');
        });
    }
}
