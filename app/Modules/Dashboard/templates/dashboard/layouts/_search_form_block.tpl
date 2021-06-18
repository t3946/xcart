<form action="{url 'dashboard:search'}" method="GET" class="search-form dashboard-search-form">
    {include 'dashboard/_filter_fields.tpl'}

    <button>Search</button>
    <button name="search[reset]" value="reset">Reset</button>
</form>