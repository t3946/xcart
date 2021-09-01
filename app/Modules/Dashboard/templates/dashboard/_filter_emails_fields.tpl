
<fieldset class="{if $full_expanded}expanded-force{/if} collapsible" rel="1">
    <legend>General</legend>

    <ul class="ul-main">
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_total">Field:</label>
                </div>

                <div class="columns large-5">
                    <select name="search[email][field]" id="email_field" class="big">
                        <option value=""></option>
                        {foreach Modules\Forms\Models\EmailModel::getFieldsName() as $key=>$value}
                            <option value="{raw $key}" {if $form_data.email.field == $key}selected{/if}>{raw $value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </li>
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="email_condition">Condition:</label>
                </div>

                <div class="columns large-5">
                    <select name="search[email][condition]" id="email_condition" class="big">
                        <option value="contains" {if $form_data.email.condition == 'contains'}selected{/if}>contains</option>
                        <option value="regexp" {if $form_data.email.condition == 'regexp'}selected{/if}>regexp</option>
                    </select>
                </div>
            </div>
        </li>
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="email_condition_value">Value:</label>
                </div>

                <div class="columns large-5">
                    <input name="search[email][value]" id="email_condition_value" value="{$form_data.email.value}" class="big"/>
                </div>
            </div>
        </li>
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="email_condition_contains_action">Action required:</label>
                </div>

                <div class="columns large-5">
                    <input type="checkbox"
                           name="search[email][contains_action]"
                           id="email_condition_contains_action"
                            {if $form_data.email.contains_action == 1}
                                checked
                            {/if}
                           value="1"
                           />
                </div>
            </div>
        </li>
    </ul>

</fieldset>