<div style="float:left">
    {$per_page_text}:&nbsp;
    <input name="per_page" type="text" value="{$per_page}" size="2">&nbsp;
</div>
{literal}
<script type="text/javascript">
    function replaceQueryParam(param, newval, search) {
        var regex = new RegExp("([?;&])" + param + "[^&;]*[;&]?");
        var query = search.replace(regex, "$1").replace(/&$/, '');
        return (query.length > 2 ? query + "&" : "?") + (newval ? param + "=" + newval : '');
    }
    $('input[name=per_page]').change(function(){
        var tempArray = location.search.split("?");
        var baseURL = tempArray[0];
        window.location = baseURL + replaceQueryParam('per_page', $(this).val(), window.location.search)
    })
</script>
{/literal}