<script src="{$SkinDir}/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>

<script type="text/javascript">
    //<![CDATA[
    {literal}

    tinymce.init({
        selector: "textarea.new_editor",
        resize: "both",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste fullpage"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        forced_root_block : false,
        force_br_newlines : true,
        force_p_newlines : false,
        convert_urls: false,
        relative_urls: false
    });

    {/literal}
    //]]>
</script>

{include file="page_title.tpl" title='Send W-9 form'}
{capture name=dialog}

{include file="customer/main/navigation.tpl"}
<form name="w9_form" method="POST">
<table cellpadding="3" cellspacing="1" width="100%">
    <tr class="w9form_instructions">
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
            <input placeholder="{$lng.lbl_contact_placeholder_email}" style="width:60%" name="send_w9_form_email" type="text" />
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td width="37%">
            Fax #:
        </td>
        <td width="60%">
            <input placeholder="{$lng.lbl_contact_placeholder_phone}" style="width:60%" name="send_w9_form_fax" type="text" />
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td class="TableSubHead" width="37%">
            Requester name:
        </td>
        <td width="60%" class="TableSubHead">
            <input placeholder="{$lng.lbl_fill_in_examples_firstname}" style="width:60%"name="send_w9_form_name" type="text" />
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td width="37%">
            Requester organization:
        </td>
        <td>
            <input placeholder="{$lng.lbl_fill_in_examples_Company_name}" style="width:60%" name="send_w9_form_organization" type="text" />
        </td>
    </tr>
    <tr>
        <td width="3%">
        </td>
        <td width="37%">
            Phone
        </td>
        <td>
            <input style="width:60%" type="text" id="phone" name="send_w9_form_phone" size="32" maxlength="32" placeholder="{$lng.lbl_contact_placeholder_phone}" />
            {$lng.lbl_phone_ext}
            <input type="text" id="phone_ext" name="send_w9_form_phone_ext" size="6" maxlength="6" placeholder="{$lng.lbl_fill_in_examples_phone_ext}" />
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td class="TableSubHead" width="37%">
            Subject line:
        </td>
        <td width="60%" class="TableSubHead">
            <input style="width:98%" name="send_w9_form_subject" type="text" value="{$config.W9_Form.w9_subject_line}"/>
        </td>
    </tr>
    <tr>
        <td width="3%"></td>
        <td width="37%">
            Message:
        </td>
        <td width="60%">
            <textarea class="new_editor" rows="30" cols="60" style="width:100%; height: 200px;" name="send_w9_form_message">{$config.W9_Form.w9_message}</textarea>
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
        <td width="37%"></td>
        <td>
            <i>Confirm with the requester that W-9 form has been received.</i>
        </td>

    </tr>
</table>
</form>
{/capture}

{include file="dialog.tpl" title="Send W-9 form" content=$smarty.capture.dialog extra='width="100%"'}