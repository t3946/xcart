<section id="new-group-container" style="display: none">
    <section id="new-group" class="admin">
        <div class="row">
            <div class="columns large-12">
                <h1>New Group Product</h1>
            </div>
        </div>
        <form method="post" name="group_form" action="{url $url id=$id}">
            <input id="o-group-manufacturer" type="hidden" name="group[manufacturerid]" value="">
            <input id="o-group-storefront" type="hidden" name="group[sfid]" value="">
            <input id="o-group-option" type="hidden" name="group[group_option]" value="">
            <ul class="ul-main">
                <li>
                    <div class="row">
                        <div class="columns large-4">
                            <label for="o-group-sku">Group product SKU</label>
                        </div>
                        <div class="columns large-8">
                            <input id="o-group-sku" name="group[sku]" type="text" placeholder="Group product SKU" class="big"/>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <div class="columns large-4">
                            <label for="o-group-title">Group product title</label>
                        </div>
                        <div class="columns large-8">
                            <input id="o-group-title" name="group[title]" type="text" placeholder="Group product title" class="big"/>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <div class="columns large-4">
                            <label for="o-group-truncate">Truncate general product name</label>

                        </div>
                        <div class="columns large-8">
                            <input class="group-truncate-checkbox" name="group[truncate_checkbox]" type="checkbox"/>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <div class="columns large-4">
                            <label for="o-group-truncate">Truncate mask</label>
                        </div>
                        <div class="columns large-8">
                            <input disabled id="o-group-truncate" name="group[truncate_mask]" type="text" class="big"/>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <div class="columns large-4">
                            <label for="o-group-truncate">Category</label>
                        </div>
                        <div class="columns large-8" id="o-category-selector">
                            <select></select>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="row">
                        <div class="columns large-4">
                            <label for="o-group-description">Group description</label>
                        </div>
                        <div class="columns large-8">
                            <textarea name="group[description]" class="new_editor description"></textarea>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <div class="columns large-4">
                            <label>Selected products</label>
                        </div>
                        <div class="columns large-8">
                            <table class="selected-products">
                                <tr class="TableHead">
                                    <td colspan="2">
                                        Products
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </li>
                <li>
                    <input name="group[submit]" type="button" value="Confirm and group"/>
                </li>
            </ul>
        </form>
    </section>
</section>