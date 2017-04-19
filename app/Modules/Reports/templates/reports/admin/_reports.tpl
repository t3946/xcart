<form action="{url 'reports:index'}" method="GET">
<fieldset>
    <legend>Reports</legend>
    <ul class="ul-main">
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label>Select report:</label>
                </div>

                <div class="columns large-6">
                    <select type="text" name="report_select" id="report_select" class="big" autocomplete="off">
                        <option>Select report</option>
                        {foreach $reports as $report}
                            <option value="{$report->id}" {if ($model && $report->id == $model->id)}selected="selected"{/if}>
                                {$report->name}
                            </option>
                        {/foreach}
                    </select>
                </div>
                <div class="columns large-2">
                    <input type="submit" value="Load report"/>
                </div>
            </div>
        </li>
    </ul>
</fieldset>
</form>