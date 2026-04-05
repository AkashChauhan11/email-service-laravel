<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ $appName }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: Arial, Helvetica, sans-serif; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f6f8;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 32px 32px 20px; text-align: center; border-bottom: 2px solid #00A9E2;">
                            <h1 style="margin: 0 0 4px; font-size: 20px; font-weight: 700; color: #152E47; letter-spacing: 1px;">SODHA MARINE SERVICES</h1>
                            <p style="margin: 0; font-size: 13px; color: #6b7280;">New Inquiry Received</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 28px 32px;">
                            @foreach($payload as $key => $value)
                                @if($key !== 'subject' && !empty($value))
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
                                        <tr>
                                            <td style="font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 4px;">
                                                {{ ucwords(str_replace('_', ' ', $key)) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            @if($key === 'message')
                                                <td style="font-size: 14px; color: #1f2937; line-height: 1.6; background-color: #f9fafb; padding: 12px; border-radius: 6px;">
                                                    {!! nl2br(e($value)) !!}
                                                </td>
                                            @else
                                                <td style="font-size: 14px; color: #1f2937; line-height: 1.5;">
                                                    {{ $value }}
                                                </td>
                                            @endif
                                        </tr>
                                    </table>
                                @endif
                            @endforeach
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 32px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0; font-size: 12px; color: #9ca3af;">
                                {{ $appName }} &middot; {{ now()->format('d M Y, h:i A') }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
