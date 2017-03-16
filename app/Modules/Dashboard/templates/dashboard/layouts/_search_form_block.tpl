<form action="{url 'dashboard:search'}" method="GET">
    {include 'dashboard/_filter_fields.tpl'}

    <ul class="ul-main">
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="fo_nlist">New order list:</label>
                </div>

                <div class="columns large-6">
                    <input type="hidden" name="search[new_list]" value="0">
                    <input type="checkbox" name="search[new_list]" id="fo_nlist" value="1" {if $form_data.new_list}checked{/if}>
                </div>
            </div>
        </li>
    </ul>

    <button>Search</button>
    <button name="search[reset]" value="reset">Reset</button>
</form>