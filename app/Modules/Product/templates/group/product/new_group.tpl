<section id="new-group-container" style="display: none">
    <section id="new-group" class="admin">
        <div class="row">
            <div class="columns large-12">
                <h1>Group</h1>
            </div>
        </div>
        <form action="">
            <ul class="ul-main">
                <li>
                    <div class="row">
                        <div class="columns large-4">
                            <label for="o-group-title">Group product title</label>
                        </div>
                        <div class="columns large-8">
                            <input id="o-group-title" type="text" placeholder="Group title" class="big"/>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <div class="columns large-4">
                            <label for="o-group-truncate">Truncate general product name part of sibling products with
                                mask</label>
                            <input id="o-group-truncate-checkbox" type="checkbox"/>
                        </div>
                        <div class="columns large-8">
                            <input id="o-group-truncate" type="text" class="big"/>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <div class="columns large-4">
                            <label for="o-group-description">Group description</label>
                        </div>
                        <div class="columns large-8">
                            <textarea class="new_editor description"></textarea>
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
                    <input type="submit" value="Confirm and group"/>
                </li>
            </ul>
        </form>
    </section>
</section>