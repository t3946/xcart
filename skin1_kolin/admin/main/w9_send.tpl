{include file="page_title.tpl" title='Send W-9 form'}
{capture name=dialog}

{include file="customer/main/navigation.tpl"}
<form name="w9_form" method="POST">
<table cellpadding="3" cellspacing="1" width="100%">
    <tr>
        <td width="3%"></td>
        <td width="97%" colspan="2">
            {$lng.send_w9_form_instructions}
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td class="TableSubHead" width="37%">
            Email address:
        </td>
        <td width="60%" class="TableSubHead">
            <input name="send_w9_form_email" type="text" />
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td width="37%">
            Fax #:
        </td>
        <td width="60%">
            <input name="send_w9_form_fax" type="text" />
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td class="TableSubHead" width="37%">
            Requester name:
        </td>
        <td width="60%" class="TableSubHead">
            <input name="send_w9_form_name" type="text" />
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td width="37%">
            Requester organization:
        </td>
        <td>
            <input name="send_w9_form_organization" type="text" />
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td class="TableSubHead" width="37%">
            Subject line:
        </td>
        <td width="60%" class="TableSubHead">
            <input name="send_w9_form_subject" type="text" value="{$config.W9_Form.w9_subject_line}"/>
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td width="37%">
            Message:
        </td>
        <td width="60%">
            <textarea style="width:100%; height: 200px;" name="send_w9_form_message">{$config.W9_Form.w9_message}</textarea>
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td class="TableSubHead" width="37%">
            File attached:
        </td>
        <td class="TableSubHead">
            {$config.w9_form_file}
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td width="37%">

        </td>
        <td>
            <input type="submit" name="w9_submit" value="Send"/>
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td colspan="2">
            <i>Confirm with the requester that W-9 form has been received.</i>
        </td>

    </tr>
</table>
</form>
{/capture}

{include file="dialog.tpl" title="Send W-9 form" content=$smarty.capture.dialog extra='width="100%"'}