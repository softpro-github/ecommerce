<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Form Message</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f5f5; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5; padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden;">
                    <tr>
                        <td style="background-color:#000000; padding:24px 32px; text-align:center;">
                            <img src="{{ ($p = \App\Models\Setting::get('logo_white_path')) ? asset('storage/'.$p) : asset('images/logo-white.png') }}" alt="CityStyleWears" style="height:44px; width:auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="color:#d4af37; text-transform:uppercase; letter-spacing:2px; font-size:11px; margin:0 0 16px;">New Contact Form Message</p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                                <tr>
                                    <td style="font-size:13px; color:#777; padding:6px 0; width:100px;">Name</td>
                                    <td style="font-size:14px; color:#111; padding:6px 0;">{{ $senderName }}</td>
                                </tr>
                                <tr>
                                    <td style="font-size:13px; color:#777; padding:6px 0;">Email</td>
                                    <td style="font-size:14px; color:#111; padding:6px 0;">{{ $senderEmail }}</td>
                                </tr>
                                <tr>
                                    <td style="font-size:13px; color:#777; padding:6px 0;">Phone</td>
                                    <td style="font-size:14px; color:#111; padding:6px 0;">{{ $senderPhone }}</td>
                                </tr>
                            </table>

                            <p style="font-size:13px; color:#777; text-transform:uppercase; letter-spacing:1px; margin:0 0 8px;">Message</p>
                            <p style="font-size:14px; color:#111; white-space:pre-line; margin:0;">{{ $messageBody }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#000000; padding:16px 32px;">
                            <p style="color:#777; font-size:11px; margin:0;">Sent from the citystylewears.com contact form</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
