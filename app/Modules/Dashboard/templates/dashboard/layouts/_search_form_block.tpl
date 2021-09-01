<form action="{url 'dashboard:search'}" method="GET" class="search-form dashboard-search-form">
    {include 'dashboard/_filter_fields.tpl'}

    <button class="button__gray">Search</button>
    <button class="button__gray" name="search[reset]" value="reset">Reset</button>
</form>