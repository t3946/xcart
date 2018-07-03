<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width"/>
</head>
<body>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="background-color: {block 'background_color'}#f2f5f7{/block};" align="center" valign="top">
            <table cellpadding="0" cellspacing="0" border="0" width="600" style="margin-top: 70px;box-shadow: 0 13px 27px rgba(0,0,0,.05);">
                <tr>
                    <td style="background-color: #fff;" valign="top" align="center">
                        <table cellpadding="0" cellspacing="0" border="0" width="530" style="margin-top: 35px;">
                            <tr>
                                <td height="30"></td>
                            </tr>
                            <tr>
                                <td valign="top" style="font-family: Arial;text-align: left;">
                                    {autoescape true}
                                        {block 'content'}{raw $content}{/block}
                                    {/autoescape}
                                </td>
                            </tr>
                            <tr>
                                <td height="30"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>