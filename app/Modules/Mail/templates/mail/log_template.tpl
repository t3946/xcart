{extends 'mail/raw_template.tpl'}

{block 'content'}
    <table  cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td style='font-family: "Lucida Console", Monaco, monospace; font-size: 12px'>

                {raw $message|nl2br}

            </td>
        </tr>
    </table>
{/block}