/**
 * $Id: popup_info.js 63 2012-10-30 11:56:13Z skot $
 */
//Cache DOM reference
$('#popup-dialog form').live('submit',
 
  function(event){
    
    //Prevent the form from regular (non-js) submission
    event.preventDefault();
    var form = $(this);
    var data = form.serialize();
    //Submit via ajax
    $.mobile.showPageLoadingMsg();
    
    $.post(
      form.attr('action'),
      data,
      function(response) {
        
        var popup_dialog_content = $(response).children('.popup-dialog-content').html();
        
        if (popup_dialog_content) {
          $('#popup-dialog .popup-dialog-content').html(
            popup_dialog_content
            );
          $('#popup-dialog').trigger('create');
              
        } else {
          
          $('#popup-dialog').dialog('close');
          
        }
        
        $.mobile.hidePageLoadingMsg();
          
        return false;
        
      });
      
    return false;
  });
