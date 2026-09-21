<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password - VitaMind</title>
</head>

<body
    style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #1f2937;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
        style="background-color: #f3f4f6; padding: 40px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
                    style="max-width: 520px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025); border: 1px solid #e5e7eb;">
                    <!-- Header -->
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); padding: 32px 24px; text-align: center;">
                            <div
                                style="display: inline-block; background-color: rgba(255, 255, 255, 0.2); padding: 8px 16px; border-radius: 9999px; margin-bottom: 12px;">
                                <span
                                    style="color: #ffffff; font-weight: 700; font-size: 18px; letter-spacing: 0.5px;">VitaMind</span>
                            </div>
                            <h1 style="color: #ffffff; font-size: 22px; font-weight: 700; margin: 0;">Reset Password
                            </h1>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 32px 28px;">
                            <p style="margin: 0 0 16px 0; font-size: 15px; line-height: 1.6; color: #374151;">
                                Halo,
                            </p>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #4b5563;">
                                Kami menerima permintaan untuk mereset password akun <strong>VitaMind</strong> Anda.
                                Gunakan kode OTP berikut untuk melanjutkan:
                            </p>

                            <!-- OTP Box -->
                            <div
                                style="background-color: #f8fafc; border: 2px dashed #6366f1; border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 24px;">
                                <span
                                    style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; color: #6b7280; display: block; margin-bottom: 8px;">Kode
                                    OTP Anda</span>
                                <div
                                    style="font-size: 36px; font-weight: 800; font-family: 'Courier New', Courier, monospace; letter-spacing: 10px; color: #4f46e5; margin: 4px 0 8px 10px;">
                                    {{ $otp }}
                                </div>
                                <span style="font-size: 13px; color: #ef4444; font-weight: 500;">
                                    ⏱️ Berlaku selama <strong>10 menit</strong>
                                </span>
                            </div>

                            <!-- Security Note -->
                            <div
                                style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 14px 16px; border-radius: 8px; margin-bottom: 24px;">
                                <p style="margin: 0; font-size: 13px; line-height: 1.5; color: #92400e;">
                                    🔒 <strong>Keamanan:</strong> Jangan berikan kode OTP ini kepada siapapun. Jika Anda
                                    tidak merasa meminta reset password, abaikan email ini.
                                </p>
                            </div>

                            <p style="margin: 0; font-size: 14px; color: #6b7280; line-height: 1.5;">
                                Salam,<br>
                                <strong style="color: #374151;">Tim VitaMind</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f9fafb; padding: 20px 24px; text-align: center; border-top: 1px solid #f3f4f6;">
                            <p style="margin: 0; font-size: 12px; color: #9ca3af; line-height: 1.5;">
                                &copy; {{ date('Y') }} VitaMind. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
