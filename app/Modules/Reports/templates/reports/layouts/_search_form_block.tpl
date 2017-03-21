<form action="{url 'reports:index'}" method="GET">
    {include 'dashboard/_filter_fields.tpl'}

    {include 'reports/_report_fields.tpl'}

    <button>Generate HTML report</button>
    <button name="report[reset]" value="reset">Generate CSV report</button>
    <button name="report[reset]" value="reset">Generate 'Time to dispatch' distribution</button>
    <button name="report[reset]" value="reset">Plot report</button>

</form>