<?php
$message = '
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="format-detection" content="date=no"/>
    <meta name="format-detection" content="address=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="x-apple-disable-message-reformatting"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet"/>
    <title>Reset Your Password</title>
</head>

<body style="margin: 0; padding: 0; font-family: \'Manrope\', sans-serif; min-height: 100vh; background: #EBFAFA;">
<center>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background: #EBFAFA;">
        <tr>
            <td align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="padding: 40px 0;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="text-align:center; padding-bottom: 20px;">
                                        <a href="#" target="_blank">
                                            <img src="images/logo/logo-fill.svg" border="0" alt="Company Logo"/>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 24px; line-height: 28px; font-weight: bold; color: #333; text-align:center; padding-bottom: 20px;">
                                        Reset Your Password
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background: #ffffff; border-radius: 8px; padding: 40px;">
                                        <p style="font-size: 16px; line-height: 24px; color: #555;">
                                            Hello,
                                            <br/><br/>
                                            You recently requested to reset your password for your account. Click the button below to reset it. This password reset is only valid for the next 15 minutes.
                                        </p>                         
                                        <p style="text-align: center; margin: 30px 0;">
                                            <a href="'. @$env['APP_URL']. '/reset/login/' . @$verify_code . '" target="_blank" style="background: #0010F7; color: #ffffff; border-radius: 8px; padding: 12px 24px; text-decoration: none; font-weight: bold;">
                                                Reset Password
                                            </a>
                                        </p>
                                        <p style="font-size: 14px; line-height: 20px; color: #888;">
                                            If you did not request a password reset, please ignore this email or contact support if you have any questions.
                                            <br/><br/>
                                            If the button above does not work, copy and paste the link below into your web browser:
                                            <br/><br/>
                                            <a href="'. @$env['APP_URL']. 'reset-password?token=abcd1234' .'" target="_blank" style="color: #0010F7; text-decoration: none;">'. $env['APP_URL']. 'reset-password?token=abcd1234</a>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 12px; color: #aaa; text-align: center; padding-top: 30px;">
                                        &copy; 2024 Company Name. All rights reserved.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</center>
</body>
</html>
';
?>
