<form action="{url 'reports:create_report'}" method="GET">

    <label for="report_name_input"><strong>Report name:</strong></label>
    <input required class="big" type="text" name="report_name" id="report_name_input" placeholder="Enter a report name"/>

    {include 'core/form/buttons.tpl'}
</form>