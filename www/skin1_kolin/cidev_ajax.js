// Retrieves the XMLHttpRequest object //
function cidev_createHttpRequestObject(){
 var xmlHttp;

 if (window.XMLHttpRequest) {
  try{
   xmlHttp = new XMLHttpRequest();
  } catch (e) {
   xmlHttp = false;
  }
 }

 // IE6 //
 else if (!xmlHttp && window.ActiveXObject){
  try{
   xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
  } catch (e) {
   xmlHttp = false;
  }
 }

 // Return the object or display an error message //
 if (!xmlHttp)
  cidev_Error('XMLHttpRequest', 'Y');
 else
  return xmlHttp;
}

function cidev_Error(error, show_alert) {
 if (show_alert == 'Y'){
  if (error == 'XMLHttpRequest') alert('There was an Error creating the XMLHttpRequest object.');
  else if (error == 'no_server') alert('There was a problem accessing the server.');
 }
}

function cidev_id$(id) {
    return document.getElementById(id)
}


function surfMetaRegister() {
    $.post('/api/analytics?_=' + (new Date()).getTime(), {
        'url': window.location.href,
        'referer': document.referer ? document.referer : ''
    });
}


$(document).ready(function(){
    surfMetaRegister();
});
