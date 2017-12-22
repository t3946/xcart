<tr>
    <td></td>
    <td class="TableSubHead">W-9 form file:</td>
    <td class="TableSubHead">
        <input accept=".pdf" type="file" name="w9_form_file" id="w9_form_file" style="display:none;"/>
        <input type="button" onclick="$(this).prev().click();" value="Choose File" />
        <span id='w9_form_file_val'>{$oW9FormConfig->getValue()}</span>
        <script type="text/javascript">
            $("#w9_form_file").change(function(){literal}{{/literal}
                $('#w9_form_file_val').text(this.value);
                {literal}}{/literal})
        </script>
    </td>
</tr>