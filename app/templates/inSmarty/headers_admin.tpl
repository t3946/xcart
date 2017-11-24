<link rel="stylesheet" href="/static/backend/dist/css/main.css?v={backend_version resource='main.css'}">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<script src="/static/backend/dist/js/main.js?v={backend_version resource='main.js'}"></script>
<script src="/static/backend/dist/raw/editor/tinymce.min.js"></script>
<script>
    $(function () {
        {set $messages = $.app->flash->read()}

        window['flashStack'] = [];

        {foreach $messages as $item}
        window['flashStack'].push({ 'message': {$item['message']|json_encode}, 'type': {$item['type']|json_encode}, 'time': {$item['time']|json_encode} });
        {/foreach}


        $(document).ready(function(){
            tinymce.init({
                selector: 'textarea.new_editor',
                plugins: [
                    'advlist autolink link image autoresize colorpicker autosave lists charmap print preview hr anchor',
                    'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime image imagetools media nonbreaking',
                    'save table contextmenu directionality emoticons template paste textcolor textpattern layer contextmenu'
                ],
                // content_css: '/static/frontend/dist/css/main.css?t=' + new Date().getTime(),
                relative_urls: false,
                browser_spellcheck : true,
                file_browser_callback: function(field_name, url, type, win) {
                    window.file_browser_window = win;
                    window.file_browser_field = field_name;
                    window.file_browser_url = url;
                    window.file_browser_type = type;
                    var base_url = "{url route="editor:index"}";
                    base_url += (base_url.indexOf('?') !== -1) ? '&':'?';

                    $('<a/>').attr('href', base_url+ "field=" + field_name + "&url=" + url).modal();
                    return false;
                },
                images_upload_handler: function(blobInfo, success, failure){
                    var xhr, formData;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST','{url route="editor:changed"}');
                    xhr.onload = function() {
                        var json;
                        if (xhr.status != 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                        json = JSON.parse(xhr.responseText);
                        success(json.url);
                    };
                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                }
            });
        })
    });
</script>




<script id="adaptives_script" type="text/javascript" language="JavaScript 1.2"></script>

{get_assets type="css" position='head'}
{get_assets type="js" position='head'}