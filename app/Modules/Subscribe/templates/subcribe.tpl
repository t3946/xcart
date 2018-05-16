<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" dir="ltr">
<head>
    <!--[if gte mso 15]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->

    {block 'seo'}{meta controller=$this!:null}{/block}

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style type="text/css">
        body{
            font:14px/20px 'Helvetica', Arial, sans-serif;
            margin:0;
            padding:75px 0 0 0;
            text-align:center;
            -webkit-text-size-adjust:none;
        }
        p{
            padding:0 0 10px 0;
        }
        h1 img{
            max-width:100%;
            height:auto !important;
            vertical-align:bottom;
        }
        h2{
            font-size:22px;
            line-height:28px;
            margin:0 0 12px 0;
        }
        h3{
            margin:0 0 12px 0;
        }
        .headerBar{
            background:none;
            padding:0;
            border:none;
        }
        .wrapper{
            width:600px;
            margin:0 auto 10px auto;
            text-align:left;
        }
        input.formEmailButton{
            border:none !important;
        }
        .formEmailButton{
            display:inline-block;
            font-weight:500;
            font-size:16px;
            line-height:42px;
            font-family:'Helvetica', Arial, sans-serif;
            width:auto;
            white-space:nowrap;
            height:42px;
            margin:12px 5px 12px 0;
            padding:0 22px;
            text-decoration:none;
            text-align:center;
            cursor:pointer;
            border:0;
            border-radius:3px;
            vertical-align:top;
        }
        .formEmailButton span{
            display:inline;
            font-family:'Helvetica', Arial, sans-serif;
            text-decoration:none;
            font-weight:500;
            font-style:normal;
            font-size:16px;
            line-height:42px;
            cursor:pointer;
            border:none;
        }
        .rounded6{
            border-radius:6px;
        }
        .poweredWrapper{
            padding:20px 0;
            width:560px;
            margin:0 auto;
        }
        .poweredBy{
            display:block;
        }
        span.or{
            display:inline-block;
            height:32px;
            line-height:32px;
            padding:0 5px;
            margin:5px 5px 0 0;
        }
        .clear{
            clear:both;
        }
        .profile-list{
            display:block;
            margin:15px 20px;
            padding:0;
            list-style:none;
            border-top:1px solid #eee;
        }
        .profile-list li{
            display:block;
            margin:0;
            padding:5px 0;
            border-bottom:1px solid #eee;
        }
        html[dir=rtl] .wrapper,html[dir=rtl] .container,html[dir=rtl] label{
            text-align:right !important;
        }
        html[dir=rtl] ul.interestgroup_field label{
            padding:0;
        }
        html[dir=rtl] ul.interestgroup_field input{
            margin-left:5px;
        }
        html[dir=rtl] .hidden-from-view{
            right:-5000px;
            left:auto;
        }
        body,#bodyTable{
            background-color:#eeeeee;
        }
        h1{
            font-size:28px;
            line-height:110%;
            margin-bottom:30px;
            margin-top:0;
            padding:0;
        }
        #templateContainer{
            background-color:none;
        }
        #templateBody{
            background-color:#ffffff;
        }
        .bodyContent{
            line-height:150%;
            font-family:Helvetica;
            font-size:14px;
            color:#333333;
            padding:20px;
        }
        a:link,a:active,a:visited,a{
            color:#336699;
        }
        .formEmailButton:link,.formEmailButton:active,.formEmailButton:visited,.formEmailButton,.formEmailButton span{
            background-color:#5d5d5d !important;
            color:#ffffff !important;
        }
        .formEmailButton:hover{
            background-color:#444444 !important;
            color:#ffffff !important;
        }
        label{
            line-height:150%;
            font-family:Helvetica;
            font-size:16px;
            color:#5d5d5d;
        }
        .field-group input,select,textarea,.dijitInputField{
            font-family:Helvetica;
            color:#5d5d5d !important;
        }
        .asterisk{
            color:#cc6600;
            font-size:20px;
        }
        label .asterisk{
            visibility:hidden;
        }
        .indicates-required{
            display:none;
        }
        .field-help{
            color:#777;
        }
        .error,.errorText{
            color:#e85c41;
            font-weight:bold;
        }
        @media (max-width: 620px){
            body{
                width:100%;
                -webkit-font-smoothing:antialiased;
                padding:10px 0 0 0 !important;
                min-width:300px !important;
            }

        }	@media (max-width: 620px){
            .wrapper,.poweredWrapper{
                width:auto !important;
                max-width:600px !important;
                padding:0 10px;
            }

        }	@media (max-width: 620px){
            #templateContainer,#templateBody,#templateContainer table{
                width:100% !important;
                -moz-box-sizing:border-box;
                -webkit-box-sizing:border-box;
                box-sizing:border-box;
            }

        }	@media (max-width: 620px){
            .addressfield span{
                width:auto;
                float:none;
                padding-right:0;
            }

        }	@media (max-width: 620px){
            .captcha{
                width:auto;
                float:none;
            }

        }		.gdpr-mergeRow{
                     margin:10px 0;
                     color:#4a4a4a;
                     font-family:Helvetica;
                 }
        .gdpr-content{
            margin:0 -20px 0 -20px;
            padding:20px 20px 10px 20px;
            background:rgba(255, 255, 255, 0.8);
        }
        .gdpr-content p{
            font-size:13px;
            line-height:1.5;
        }
        .gdpr-content label{
            color:#4a4a4a;
            font-family:Helvetica;
        }
        .gdpr-content .checkbox-group label{
            font-weight:normal;
            font-size:13px;
        }
        .gdpr-footer{
            margin:0 -20px 0 -20px;
            padding:20px;
            background:rgba(255, 255, 255, 0.9);
            overflow:auto;
            color:#4a4a4a;
        }
        .gdpr-footer p{
            font-size:11px;
            line-height:1.5;
            margin-bottom:0;
        }
        .gdpr-footer a{
            color:#206578;
        }
        .gdpr-footer img{
            width:65px;
            float:left;
            margin-right:15px;
        }
    </style></head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="font: 14px/20px 'Helvetica', Arial, sans-serif;margin: 0;padding: 75px 0 0 0;text-align: center;-webkit-text-size-adjust:none;background-color: #eeeeee;">
<center>
    <table border="0" cellpadding="20" cellspacing="0" height="100%" width="100%" id="bodyTable" style="background-color: #eeeeee;">
        <tr>
            <td align="center" valign="top">
                <!-- // BEGIN CONTAINER -->
                <!--[if gte mso 9]>
                <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
                    <tr>
                        <td align="center" valign="top" width="600" style="width:600px;">
                <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;border-radius: 6px;background-color: none;" id="templateContainer" class="rounded6">
                    <tr>
                        <td align="center" valign="top">
                            <!-- // BEGIN HEADER -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px">
                                <tr>
                                    <td>
                                        <h1 style="font-size: 28px;line-height: 110%;margin-bottom: 30px;margin-top: 0;padding: 0;">Artist Supply Source</h1>
                                    </td>
                                </tr>
                            </table>
                            <!-- END HEADER \\ -->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <!-- // BEGIN BODY -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;border-radius: 6px;background-color: #ffffff;" id="templateBody" class="rounded6">
                                <tr>

                                    <td align="left" valign="top" class="bodyContent" style="line-height: 150%;font-family: Helvetica;font-size: 14px;color: #333333;padding: 20px;">

                                        <h2 style="font-size: 22px;line-height: 28px;margin: 0 0 12px 0;">Please Confirm Subscription
                                        </h2>
                                        <a class="formEmailButton" href="https://artistsupplysource.us2.list-manage.com/subscribe/confirm?u=1b7ac0148d1e17c19772483c7&id=3ffb82f7e0&e=ccbf65a8a0" style="color: #ffffff !important;display: inline-block;font-weight: 500;font-size: 16px;line-height: 42px;font-family: 'Helvetica', Arial, sans-serif;width: auto;white-space: nowrap;height: 42px;margin: 12px 5px 12px 0;padding: 0 22px;text-decoration: none;text-align: center;cursor:pointer;border: 0;border-radius: 3px;vertical-align: top;background-color:#5d5d5d !important;"><span style="display: inline;font-family: 'Helvetica', Arial, sans-serif;text-decoration: none;font-weight: 500;font-style: normal;font-size: 16px;line-height: 42px;cursor: pointer;border: none;background-color: #5d5d5d !important;color: #ffffff !important;">Yes, subscribe me to this list.</span></a>
                                        <br>
                                        <div><p style="padding: 0 0 10px 0;">If you received this email by mistake, simply delete it. You won't be subscribed if you don't click the confirmation link above.</p>
                                            <p style="padding: 0 0 10px 0;">For questions about this list, please contact:
                                                <br><a href="mailto:mailchimp@s3stores.com" style="color: #336699;">mailchimp@s3stores.com</a></p>
                                        </div>


                                        <span itemscope itemtype="http://schema.org/EmailMessage">
  <span itemprop="description" content="We need to confirm your email address."></span>
  <span itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction">
    <meta itemprop="name" content="Confirm Subscription">
    <span itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler">
      <meta itemprop="url" content="https://artistsupplysource.us2.list-manage.com/subscribe/smartmail-confirm?u=1b7ac0148d1e17c19772483c7&id=3ffb82f7e0&e=ccbf65a8a0&inline=true">
      <link itemprop="method" href="http://schema.org/HttpRequestMethod/POST">
    </span>
            </span>
            </span>


                                    </td>

                                </tr>
                            </table>
                            <!-- END BODY \\ -->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <!-- // BEGIN FOOTER -->
                            <table border="0" cellpadding="20" cellspacing="0" width="100%" style="max-width:600px">
                                <tr>
                                    <td align="center" valign="top">

                                        <div>
                                            <span class="poweredBy" style="display: block;"><a href="http://www.mailchimp.com/monkey-rewards/?utm_source=freemium_newsletter&utm_medium=email&utm_campaign=monkey_rewards&aid=1b7ac0148d1e17c19772483c7&afl=1" style="color: #336699;"><img src="https://gallery.mailchimp.com/089443193dd93823f3fed78b4/images/postmark_mailchimp_red.gif" border="0" alt="Email Marketing Powered by MailChimp" title="MailChimp Email Marketing"></a></span>
                                        </div>

                                    </td>
                                </tr>
                            </table>
                            <!-- END FOOTER \\ -->
                        </td>
                    </tr>
                </table>
                <!--[if gte mso 9]>
                </td>
                </tr>
                </table>
                <![endif]-->
                <!-- END CONTAINER \\ -->
            </td>
        </tr>
    </table>
</center>
</body>
</html>